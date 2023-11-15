# -*- coding: utf-8 -*-
"""code.ipynb

Automatically generated by Colaboratory.

Original file is located at
    https://colab.research.google.com/drive/1a6MWyknYnpHptKlsbo4hTc4YIVHQ8YZf
"""

import pandas as pd
import matplotlib.pyplot as plt
import numpy as np
from tqdm.auto import tqdm
from sklearn.metrics import classification_report
from sklearn import metrics
from scipy.special import softmax

# install datasets
!pip install datasets
from datasets import load_dataset, load_metric, Dataset, DatasetDict

# install transformers
!pip install transformers==4.28.0
from transformers import AutoTokenizer
from transformers import AutoModelForSequenceClassification
from transformers import TrainingArguments, Trainer
from transformers import DefaultDataCollator

dataset = DatasetDict()
dataset["train"] = Dataset.from_pandas(pd.read_csv("train_data.csv"))
dataset["test"] = Dataset.from_pandas(pd.read_csv("test_data.csv"))
dataset["valid"] = Dataset.from_pandas(pd.read_csv("valid_data.csv"))
dataset

# RoBERTa Model for English Classification
tokenizer = AutoTokenizer.from_pretrained("roberta-base")
model = AutoModelForSequenceClassification.from_pretrained("roberta-base", num_labels=3)

metric = load_metric("accuracy")
def compute_metrics(eval_pred):
    logits, labels = eval_pred
    predictions = np.argmax(logits, axis=-1)
    return metric.compute(predictions=predictions, references=labels)

str_to_int = {"quran": 0, "bible": 1, "mizan": 2}
def tokenize_function(examples):
    tokenized_batch = tokenizer(examples["source"], truncation=True, max_length=128)
    tokenized_batch["label"] = [str_to_int[label] for label in examples["category"]]
    return tokenized_batch

tokenized_datasets = dataset.map(tokenize_function, batched=True)

training_args = TrainingArguments(
    output_dir="roberta",
    evaluation_strategy="epoch",
    logging_steps = 20,
    learning_rate=3e-5,
    num_train_epochs=8,
    per_device_train_batch_size=32,
    per_device_eval_batch_size=32,
    save_total_limit = 1,
    group_by_length = True,
    seed=0,
)
trainer = Trainer(
    model=model,
    args=training_args,
    train_dataset=tokenized_datasets["train"],
    eval_dataset=tokenized_datasets["valid"],
    tokenizer=tokenizer,
    compute_metrics=compute_metrics,
)
trainer.train()

pred = trainer.predict(tokenized_datasets["test"])
y_pred = pred.predictions.argmax(axis=-1)

print(trainer.evaluate(tokenized_datasets["test"]))
print(classification_report(tokenized_datasets["test"]["label"], y_pred, target_names=str_to_int.keys()))
print("AUC-ovr", metrics.roc_auc_score(tokenized_datasets["test"]["label"], softmax(pred.predictions, axis=-1), multi_class="ovr"))
print("AUC-ovo", metrics.roc_auc_score(tokenized_datasets["test"]["label"], softmax(pred.predictions, axis=-1), multi_class="ovo"))

# ParsBert Model for Persian Classification
model_checkpoint = "HooshvareLab/bert-base-parsbert-uncased"
tokenizer = AutoTokenizer.from_pretrained(model_checkpoint)
model = AutoModelForSequenceClassification.from_pretrained(model_checkpoint, num_labels=3)

metric = load_metric("accuracy")
def compute_metrics(eval_pred):
    logits, labels = eval_pred
    predictions = np.argmax(logits, axis=-1)
    return metric.compute(predictions=predictions, references=labels)

str_to_int = {"quran": 0, "bible": 1, "mizan": 2}
def tokenize_function(examples):
    tokenized_batch = tokenizer(examples["targets"], truncation=True, max_length=128)
    tokenized_batch["label"] = [str_to_int[label] for label in examples["category"]]
    return tokenized_batch

tokenized_datasets = dataset.map(tokenize_function, batched=True)
print(tokenized_datasets["train"][0])
tokenized_datasets

training_args = TrainingArguments(
    output_dir="parsbert",
    evaluation_strategy="epoch",
    logging_steps = 20,
    learning_rate=3e-5,
    num_train_epochs=10,
    per_device_train_batch_size=32,
    per_device_eval_batch_size=32,
    save_total_limit = 1,
    group_by_length = True,
    seed=0,
)
trainer = Trainer(
    model=model,
    args=training_args,
    train_dataset=tokenized_datasets["train"],
    eval_dataset=tokenized_datasets["valid"],
    tokenizer=tokenizer,
    compute_metrics=compute_metrics,
)
trainer.train()

pred = trainer.predict(tokenized_datasets["test"])
y_pred = pred.predictions.argmax(axis=-1)

print(trainer.evaluate(tokenized_datasets["test"]))
print(classification_report(tokenized_datasets["test"]["label"], y_pred, target_names=str_to_int.keys()))
print("AUC-ovr", metrics.roc_auc_score(tokenized_datasets["test"]["label"], softmax(pred.predictions, axis=-1), multi_class="ovr"))
print("AUC-ovo", metrics.roc_auc_score(tokenized_datasets["test"]["label"], softmax(pred.predictions, axis=-1), multi_class="ovo"))

# Multilingual XLM-RoBERTa Model
model_checkpoint = "xlm-roberta-base"
tokenizer = AutoTokenizer.from_pretrained(model_checkpoint)
model = AutoModelForSequenceClassification.from_pretrained(model_checkpoint, num_labels=3)

metric = load_metric("accuracy")
def compute_metrics(eval_pred):
    logits, labels = eval_pred
    predictions = np.argmax(logits, axis=-1)
    return metric.compute(predictions=predictions, references=labels)

str_to_int = {"quran": 0, "bible": 1, "mizan": 2}
def tokenize_function(examples):
    tokenized_batch = tokenizer(examples["source"], examples["targets"], truncation=True, max_length=128)
    tokenized_batch["label"] = [str_to_int[label] for label in examples["category"]]
    return tokenized_batch

tokenized_datasets = dataset.map(tokenize_function, batched=True)
print(tokenized_datasets["train"][0])
tokenized_datasets

training_args = TrainingArguments(
    output_dir="xlm-roberta",
    evaluation_strategy="epoch",
    logging_steps = 20,
    learning_rate=3e-5,
    num_train_epochs=5,
    per_device_train_batch_size=16,
    per_device_eval_batch_size=16,
    save_total_limit = 1,
    group_by_length = True,
    seed=0,
)
trainer = Trainer(
    model=model,
    args=training_args,
    train_dataset=tokenized_datasets["train"],
    eval_dataset=tokenized_datasets["valid"],
    tokenizer=tokenizer,
    compute_metrics=compute_metrics,
)
trainer.train()

pred = trainer.predict(tokenized_datasets["test"])
y_pred = pred.predictions.argmax(axis=-1)

print(trainer.evaluate(tokenized_datasets["test"]))
print(classification_report(tokenized_datasets["test"]["label"], y_pred, target_names=str_to_int.keys()))
print("AUC-ovr", metrics.roc_auc_score(tokenized_datasets["test"]["label"], softmax(pred.predictions, axis=-1), multi_class="ovr"))
print("AUC-ovo", metrics.roc_auc_score(tokenized_datasets["test"]["label"], softmax(pred.predictions, axis=-1), multi_class="ovo"))