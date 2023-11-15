# -*- coding: utf-8 -*-
"""code.ipynb

Automatically generated by Colaboratory.

Original file is located at
    https://colab.research.google.com/drive/1wkOEluUXvjUvKGSg7F9QMBkWqEwhdsw4
"""

!pip install datasets
from datasets import load_dataset, load_metric

!pip install transformers==4.28.0
from transformers import AutoTokenizer, DefaultDataCollator, AutoModelForQuestionAnswering, TrainingArguments, Trainer, create_optimizer, pipeline

import json
import pandas as pd
import datasets
from tqdm.auto import tqdm
from matplotlib import pyplot as plt

DATASETS = {"pquad": 0}

class DatasetLoader:
    def __init__(self, dataset, tokenizer):
        self.tokenizer = tokenizer
        self.dataset = datasets.DatasetDict()
        dataset_to_loader = {
            DATASETS["pquad"]: self.__load_dataset,
        }
        self.dataset = dataset_to_loader[dataset]()
        self.tokenized_dataset = self.dataset.map(self.preprocess_function, batched=True, remove_columns=self.dataset["train"].column_names)

    def __extract_entries(self, data, limit=200000000):
        df_list = []
        c = 0
        length_distribution = []
        for d in tqdm(data['data'], desc="Converting json to dataset"):
            for p in d['paragraphs']:
                length_distribution.append(len(p['context'].split()))
                for qas in p['qas']:
                    c += 1
                    if c > limit:
                        return df_list
                    if qas["is_impossible"]:
                        continue
                        df_list.append({
                            "id": str(qas['id']),
                            "title": d['title'],
                            "context": p['context'],
                            "question": qas['question'],
                            "answers": {"text": "", "answer_start": 0}
                        })
                    else:
                        for answer in qas['answers']:
                            df_list.append({
                                "id": str(qas['id']),
                                "title": d['title'],
                                "context": p['context'],
                                "question": qas['question'],
                                "answers": {"text": answer["text"], "answer_start": answer["answer_start"]}
                            })
        plt.hist(length_distribution)
        plt.title("Length Distribution")
        plt.show()
        return df_list

    def __load_dataset(self):
        self.dataset = datasets.DatasetDict()
        for part in ["train", "validation", "test"]:
            with open(f"data/{part}_samples.json", 'r', encoding='utf-8') as f:
                data = json.load(f)
            df_list = self.__extract_entries(data)
            self.dataset[part] = datasets.Dataset.from_pandas(pd.DataFrame.from_dict(df_list))
        return self.dataset

    def preprocess_function(self, examples):
        questions = [q.strip() for q in examples["question"]]
        inputs = self.tokenizer(
            questions,
            examples["context"],
            max_length=380,
            truncation="only_second",
            return_offsets_mapping=True,
        )

        offset_mapping = inputs.pop("offset_mapping")
        answers = examples["answers"]
        start_positions = []
        end_positions = []

        for i, offset in enumerate(offset_mapping):  # offset is [(start_char, end_char), ...]
            answer = answers[i]
            start_char = answer["answer_start"]
            end_char = answer["answer_start"] + len(answer["text"])
            sequence_ids = inputs.sequence_ids(i)

            # Find the start and end of the context
            idx = 0
            while sequence_ids[idx] != 1:
                idx += 1
            context_start = idx
            while sequence_ids[idx] == 1:
                idx += 1
            context_end = idx - 1

            # If the answer is not fully inside the context, label it (0, 0)
            if offset[context_start][0] > end_char or offset[context_end][1] < start_char:
                start_positions.append(0)
                end_positions.append(0)
            else:
                # Otherwise it's the start and end token positions
                idx = context_start
                while idx <= context_end and offset[idx][0] <= start_char:
                    idx += 1
                start_positions.append(idx - 1)

                idx = context_end
                while idx >= context_start and offset[idx][1] >= end_char:
                    idx -= 1
                end_positions.append(idx + 1)

        inputs["start_positions"] = start_positions
        inputs["end_positions"] = end_positions
        return inputs

class TrainerQA:
    def __init__(self, model_checkpoint, dataset):
        self.tokenizer = AutoTokenizer.from_pretrained(model_checkpoint)
        self.dataset_loader = DatasetLoader(dataset, self.tokenizer)
        self.model = AutoModelForQuestionAnswering.from_pretrained(model_checkpoint)

    def train(self, num_train_epochs=3, learning_rate=2e-5):
        data_collator = DefaultDataCollator()
        training_args = TrainingArguments(
            output_dir="./results",
            evaluation_strategy="epoch",
            learning_rate=learning_rate,
            per_device_train_batch_size=16,
            per_device_eval_batch_size=16,
            num_train_epochs=num_train_epochs,
            weight_decay=0.01,
            group_by_length=True,
            logging_steps=20
        )
        self.trainer = Trainer(
            model=self.model,
            args=training_args,
            train_dataset=self.dataset_loader.tokenized_dataset["train"],
            eval_dataset=self.dataset_loader.tokenized_dataset["validation"],
            tokenizer=self.tokenizer,
        )
        self.trainer.train()

    def evaluate(self):
        qa_model = pipeline("question-answering", model=self.model, tokenizer=self.tokenizer, device=0)
        questions = self.dataset_loader.dataset["test"]["question"]
        contexts = self.dataset_loader.dataset["test"]["context"]
        preds = qa_model(question = questions, context = contexts, device="cuda")

        metric = load_metric("squad")
        last_id = -1
        predictions, references = [], []
        for i, answers in tqdm(enumerate(self.dataset_loader.dataset["test"]["answers"])):
            if len(answers["text"]) < 1:
                continue
            id = self.dataset_loader.dataset["test"][i]["id"]
            if id != last_id:
                predictions.append({
                    "id": id,
                    "prediction_text": preds[i]["answer"].strip()
                })
                references.append({
                    "id": id,
                    "answers": []
                })
                last_id = id
            references[-1]["answers"].append(answers)
        results = metric.compute(predictions=predictions, references=references)
        print(results)
        return results

"""## Traning using BERT"""

model_checkpoint = "HooshvareLab/bert-base-parsbert-uncased"
dataset = DATASETS["pquad"]
trainer = TrainerQA(model_checkpoint, dataset)
trainer.train()
trainer.evaluate()

"""## Training using ALBERT"""

model_checkpoint = "m3hrdadfi/albert-fa-base-v2"
dataset = DATASETS["pquad"]
trainer = TrainerQA(model_checkpoint, dataset)
trainer.train()
trainer.evaluate()