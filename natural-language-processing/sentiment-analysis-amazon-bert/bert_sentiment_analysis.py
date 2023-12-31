# -*- coding: utf-8 -*-
"""BERT_sentiment_analysis.ipynb

Automatically generated by Colaboratory.

Original file is located at
    https://colab.research.google.com/drive/12eCFaVGiKl5uBBoPUmwmBlqfk37qC-H6

## **Classifying the sentiments of Amazon comments**

For this model, we will fine-tune BERT to perform the sentiment classification task. Most of the work here is based on the repo: https://github.com/sebsk/CS224N-Project

### **Classifier model**

Since we are using `BertForSequenceClassification`, the model will take in the input and mask tensors and produce an single tensor of size 1 x 768. This tensor is the BERT output of the `[CLS]` token. The `BertForSequenceClassification` model will then output this tensor to a softmax layer of size `n_class` (which in our case is 5 since we have 5 possible classes).
"""

import sys
!{sys.executable} -m pip install torch transformers pandas scikit-learn

# Define utils functions

def pad_sents(sents, pad_token):
    """ Pad list of sentences according to the longest sentence in the batch.
    @param sents (list[list[int]]): list of sentences, where each sentence
                                    is represented as a list of words
    @param pad_token (int): padding token
    @returns sents_padded (list[list[int]]): list of sentences where sentences shorter
        than the max length sentence are padded out with the pad_token, such that
        each sentences in the batch now has equal length.
        Output shape: (batch_size, max_sentence_length)
    """
    sents_padded = []

    max_len = max(len(s) for s in sents)
    batch_size = len(sents)

    for s in sents:
        padded = [pad_token] * max_len
        padded[:len(s)] = s
        sents_padded.append(padded)

    return sents_padded

def sents_to_tensor(tokenizer, sents, device):
    """
    :param tokenizer: BertTokenizer
    :param sents: list[str], list of sentences (NOTE: untokenized, continuous sentences), reversely sorted
    :param device: torch.device
    :return: sents_tensor: torch.Tensor, shape(batch_size, max_sent_length), reversely sorted
    :return: masks_tensor: torch.Tensor, shape(batch_size, max_sent_length), reversely sorted
    :return: sents_lengths: torch.Tensor, shape(batch_size), reversely sorted
    """
    tokens_list = [tokenizer.tokenize(sent) for sent in sents]
    sents_lengths = [len(tokens) for tokens in tokens_list]
    tokens_list_padded = pad_sents(tokens_list, '[PAD]')
    sents_lengths = torch.tensor(sents_lengths, device=device)

    masks = []
    for tokens in tokens_list_padded:
        mask = [0 if token=='[PAD]' else 1 for token in tokens]
        masks.append(mask)
    masks_tensor = torch.tensor(masks, dtype=torch.long, device=device)
    tokens_id_list = [tokenizer.convert_tokens_to_ids(tokens) for tokens in tokens_list_padded]
    sents_tensor = torch.tensor(tokens_id_list, dtype=torch.long, device=device)

    return sents_tensor, masks_tensor, sents_lengths

"""## **Defining the Model**"""

from transformers import BertForSequenceClassification, BertTokenizer, AdamW
import torch
from torch import nn
import torch.nn.functional as F

## Define the sentiment classification model

class SentimentClassifierModel(nn.Module):

    def __init__(self, bert_config, device, n_class):
        """
        :param bert_config: str, BERT configuration description
        :param device: torch.device
        :param n_class: int
        """

        super(SentimentClassifierModel, self).__init__()

        self.n_class = n_class
        self.bert_config = bert_config
        self.bert = BertForSequenceClassification.from_pretrained(self.bert_config, num_labels=self.n_class)
        self.tokenizer = BertTokenizer.from_pretrained(self.bert_config)
        self.device = device

    def forward(self, sents):
        """
        :param sents: list[str], list of sentences (NOTE: untokenized, continuous sentences)
        :return: pre_softmax, torch.tensor of shape (batch_size, n_class)
        """

        sents_tensor, masks_tensor, sents_lengths = sents_to_tensor(self.tokenizer, sents, self.device)
        pre_softmax = self.bert(input_ids=sents_tensor, attention_mask=masks_tensor)

        return pre_softmax

    @staticmethod
    def load(model_path: str, device):
        """ Load the model from a file.
        @param model_path (str): path to model
        @return model (nn.Module): model with saved parameters
        """
        params = torch.load(model_path, map_location=lambda storage, loc: storage)
        args = params['args']
        model = SentimentClassifierModel(device=device, **args)
        model.load_state_dict(params['state_dict'])

        return model

    def save(self, path: str):
        """ Save the model to a file.
        @param path (str): path to the model
        """
        print('save model parameters to [%s]' % path, file=sys.stderr)

        params = {
            'args': dict(bert_config=self.bert_config, n_class=self.n_class),
            'state_dict': self.state_dict()
        }

        torch.save(params, path)

"""## **Dataset**

We use the Amazon Reviews Sentiment dataset for training

The CSV file has the following rows:
- `review_id`
- `asins`
- `review_rating`
- `text`

For our purposes, we only care about the following rows:
- `review_id`
- `review_rating`
- `text`

"""

# Load dataset
import pandas
from google.colab import drive

pwd = '/content/gdrive'
drive.mount(pwd)
df= pandas.read_csv("data.csv", index_col=0, usecols=['review_id','review_rating', 'text'])
df.head()

"""## **General text preprocessing**

This process references the following repo: https://github.com/sebsk/CS224N-Project/blob/df0050357d40e7f46b9c421ade52cdb9358c831c/Text_preprocessing.ipynb
"""

df.text = [' '.join(text.split()[:80]) for text in df.text]


# Remove URL, RT, mention(@)

df.text = df.text.str.replace(r'http(\S)+', r'')
df.text = df.text.str.replace(r'http ...', r'')
df.text = df.text.str.replace(r'(RT|rt)[ ]*@[ ]*[\S]+',r'')
df.text = df.text.str.replace(r'@[\S]+',r'')

# Remove non-ascii words or characters
df.text = [''.join([i if ord(i) < 128 else '' for i in str(text)]) for text in df.text]
df.text = df.text.str.replace(r'_[\S]?',r'')

# Remove extra space
df.text = df.text.str.replace(r'[ ]{2, }',r' ')

# Remove &, < and >
df.text = df.text.str.replace(r'&amp;?',r'and')
df.text = df.text.str.replace(r'&lt;',r'<')
df.text = df.text.str.replace(r'&gt;',r'>')

# Insert space between words and punctuation marks
df.text = df.text.str.replace(r'([\w\d]+)([^\w\d ]+)', r'\1 \2')
df.text = df.text.str.replace(r'([^\w\d ]+)([\w\d]+)', r'\1 \2')

# Lowercased and strip
df.text = df.text.str.lower()
df.text = df.text.str.strip()

df.review_rating = df.review_rating.replace(1, 'one')
df.review_rating = df.review_rating.replace(2, 'two')
df.review_rating = df.review_rating.replace(3, 'three')
df.review_rating = df.review_rating.replace(4, 'four')
df.review_rating = df.review_rating.replace(5, 'five')

print(df.review_rating)

df['text_length'] = [len(text.split(' ')) for text in df.text]
print(df.shape)

# Drop texts with length <=3 and drop duplicates
df = df[df['text_length']>3]
df = df.drop_duplicates(subset=['text'])

print(df.shape)

# Summary of sample size and labels
df.shape[0]

df.review_rating.value_counts()

"""## **Preprocess text into the BERT format**"""

df['BERT_processed_text'] = '[CLS] '+df.text
df.BERT_processed_text

tokenizer = BertTokenizer.from_pretrained('bert-base-uncased')
df['BERT_processed_text_length'] = [len(tokenizer.tokenize(sent)) for sent in df.text]

df.BERT_processed_text_length

label_dict = dict()
for i, l in enumerate(list(df.review_rating.value_counts().keys())):
    label_dict.update({l: i})

df['review_rating_label'] = [label_dict[label] for label in df.review_rating]

df.review_rating_label

"""## **Save data**"""

!ls /content/gdrive/My\ Drive/Colab\ Notebooks
df.to_csv(pwd + '/My Drive/Colab Notebooks/bert_processed_amazon_review_sentiment.csv')

"""## **Training**"""

from sklearn.model_selection import train_test_split

# Define training params
label_names = ['one', 'two', 'three', 'four', 'five']
model_name = 'st-sentiment'
device = torch.device("cuda:0")
bert_size = 'bert-base-uncased'

train_batch_size = 32 # batch size
clip_grad = 1.0 # gradient clipping
log_every = 10 # number of mini-batches before logging
max_epoch = 24 # max number of epochs
max_patience = 3 # number of iterations to wait before decaying learning rate
max_num_trial = 3 # number of trials before terminating training
lr_decay = 0.5 # learning rate decay
lr_bert = 0.00002 # BERT learning rate
lr = 0.001 # learning rate
valid_niter = 500 # perform validation after n iterations
dropout = 0.3 # dropout rate
verbose = True

prefix = model_name + '_' + bert_size
model_save_path = pwd + '/My Drive/Colab Notebooks/' + prefix+'_model.bin'

# Split up data into train and validation, where validation is 20% of the dataset
training_data,validation_data = train_test_split(df,test_size=0.2,random_state=42)
print(len(df), len(training_data), len(validation_data))

print(training_data)

import pprint
pp = pprint.PrettyPrinter(indent=4)

train_label = dict(training_data.review_rating_label.value_counts())
label_max = float(max(train_label.values()))
train_label_weight = torch.tensor([label_max/train_label[i] for i in range(len(train_label))], device=device)
train_label_weight = train_label_weight.float()

pp.pprint(train_label_weight)

# Set up model and optimizer
import time
start_time = time.time()

model = SentimentClassifierModel(bert_size, device, len(label_names))
optimizer = AdamW([
        {'params': model.bert.bert.parameters()},
        {'params': model.bert.classifier.parameters(), 'lr': float(lr)}
    ], lr=float(lr_bert))

model = model.to(device)
print('Use device: %s' % device, file=sys.stderr)
print('Done! time elapsed %.2f sec' % (time.time() - start_time), file=sys.stderr)
print('-' * 80, file=sys.stderr)

# Util functions for training
import math
import logging
import pickle
import numpy as np
import torch
import pandas as pd
import sys
# from docopt import docopt
from sklearn.metrics import accuracy_score, matthews_corrcoef, confusion_matrix, \
    f1_score, precision_score, recall_score, roc_auc_score

import matplotlib
matplotlib.use('agg')
from matplotlib import pyplot as plt

def batch_iter(data, batch_size, shuffle=False, bert=None):
    """ Yield batches of sentences and labels reverse sorted by length (largest to smallest).
    @param data (dataframe): dataframe with ProcessedText (str) and label (int) columns
    @param batch_size (int): batch size
    @param shuffle (boolean): whether to randomly shuffle the dataset
    @param bert (str): whether for BERT training. Values: "large", "base", None
    """
    batch_num = math.ceil(data.shape[0] / batch_size)
    index_array = list(range(data.shape[0]))

    if shuffle:
        data = data.sample(frac=1)

    for i in range(batch_num):
        indices = index_array[i * batch_size: (i + 1) * batch_size]
        examples = data.iloc[indices].sort_values(by='BERT_processed_text_length', ascending=False)
        sents = list(examples.BERT_processed_text)

        targets = list(examples.review_rating_label.values)
        yield sents, targets  # list[list[str]] if not bert else list[str], list[int]

def validation(model, df_val, bert_size, loss_func, device):
    """ validation of model during training.
    @param model (nn.Module): the model being trained
    @param df_val (dataframe): validation dataset
    @param bert_size (str): large or base
    @param loss_func(nn.Module): loss function
    @param device (torch.device)
    @return avg loss value across validation dataset
    """
    was_training = model.training
    model.eval()

    df_val = df_val.sort_values(by='BERT_processed_text_length', ascending=False)

    ProcessedText_BERT = list(df_val.BERT_processed_text)
    InformationType_label = list(df_val.review_rating_label)

    val_batch_size = 32

    n_batch = int(np.ceil(df_val.shape[0]/val_batch_size))

    total_loss = 0.

    with torch.no_grad():
        for i in range(n_batch):
            sents = ProcessedText_BERT[i*val_batch_size: (i+1)*val_batch_size]
            targets = torch.tensor(InformationType_label[i*val_batch_size: (i+1)*val_batch_size],
                                   dtype=torch.long, device=device)
            batch_size = len(sents)
            pre_softmax = model(sents)[0]
            batch_loss = loss_func(pre_softmax, targets)
            total_loss += batch_loss.item()*batch_size

    if was_training:
        model.train()

    return total_loss/df_val.shape[0]

def plot_confusion_matrix(y_true, y_pred, classes, normalize=False, title=None, path='cm', cmap=plt.cm.Reds):
    """
    This function prints and plots the confusion matrix.
    Normalization can be applied by setting `normalize=True`.
    """
    if not title:
        if normalize:
            title = 'Normalized confusion matrix'
        else:
            title = 'Confusion matrix, without normalization'

    # Compute confusion matrix
    cm = confusion_matrix(y_true, y_pred)

    if normalize:
        cm = cm.astype('float') / cm.sum(axis=1)[:, np.newaxis]
    pickle.dump(cm, open(path, 'wb'))

    fig, ax = plt.subplots()
    im = ax.imshow(cm, interpolation='nearest', cmap=cmap)
    ax.figure.colorbar(im, ax=ax)
    # We want to show all ticks...
    ax.set(xticks=np.arange(cm.shape[1]),
           yticks=np.arange(cm.shape[0]),
           # ... and label them with the respective list entries
           xticklabels=classes, yticklabels=classes,
           title=title,
           ylabel='True label',
           xlabel='Predicted label')

    # Rotate the tick labels and set their alignment.
    plt.setp(ax.get_xticklabels(), rotation=45, ha="right",
             rotation_mode="anchor")

    # Loop over data dimensions and create text annotations.
    fmt = '.2f' if normalize else 'd'
    thresh = cm.max() / 2.
    for i in range(cm.shape[0]):
        for j in range(cm.shape[1]):
            ax.text(j, i, format(cm[i, j], fmt),
                    ha="center", va="center",
                    color="white" if cm[i, j] > thresh else "black")
    fig.tight_layout()
    return ax

# Train

model.train()
cn_loss = torch.nn.CrossEntropyLoss(weight=train_label_weight, reduction='mean')
torch.save(cn_loss, 'loss_func')  # for later testing

# Initialize training variables
num_trial = 0
train_iter = 0
patience = 0
cum_loss = 0
report_loss = 0
cum_examples = report_examples = epoch = 0
hist_valid_scores = []

! ls

train_time = begin_time = time.time()
print('Begin Maximum Likelihood training...')

# Training loop
while epoch < int(max_epoch):
    epoch += 1
    for sents, targets in batch_iter(training_data, batch_size=train_batch_size, shuffle=True, bert='base'):  # for each epoch
        train_iter += 1
        optimizer.zero_grad()
        batch_size = len(sents)
        pre_softmax = model(sents)[0]

        # Calculate loss and gradient function
        loss = cn_loss(pre_softmax, torch.tensor(targets, dtype=torch.long, device=device))
        loss.backward()

        # Next step
        optimizer.step()

        batch_losses_val = loss.item() * batch_size
        report_loss += batch_losses_val
        cum_loss += batch_losses_val

        report_examples += batch_size
        cum_examples += batch_size

        if train_iter % log_every == 0:
            print('epoch %d, iter %d, avg. loss %.2f, '
                  'cum. examples %d, speed %.2f examples/sec, '
                  'time elapsed %.2f sec' % (epoch, train_iter,
                     report_loss / report_examples,
                     cum_examples,
                     report_examples / (time.time() - train_time),
                     time.time() - begin_time), file=sys.stderr)

            train_time = time.time()
            report_loss = report_examples = 0.

        # perform validation
        if train_iter % valid_niter == 0:
            print('epoch %d, iter %d, cum. loss %.2f, cum. examples %d' % (epoch, train_iter,
                 cum_loss / cum_examples,
                 cum_examples), file=sys.stderr)

            cum_loss = cum_examples = 0.

            print('begin validation ...', file=sys.stderr)

            validation_loss = validation(model, validation_data, bert_size, cn_loss, device)   # dev batch size can be a bit larger

            print('validation: iter %d, loss %f' % (train_iter, validation_loss), file=sys.stderr)

            is_better = len(hist_valid_scores) == 0 or validation_loss < min(hist_valid_scores)
            hist_valid_scores.append(validation_loss)

            model.save(model_save_path + '.e' + str(epoch))

            if is_better:
                patience = 0
                print('save currently the best model to [%s]' % model_save_path, file=sys.stderr)

                model.save(model_save_path)
                # also save the optimizers' state
                torch.save(optimizer.state_dict(), model_save_path + '.optim')

            elif patience < int(max_patience):
                patience += 1
                print('hit patience %d' % patience, file=sys.stderr)

                if patience == int(max_patience):
                    num_trial += 1
                    print('hit #%d trial' % num_trial, file=sys.stderr)
                    if num_trial == max_num_trial:
                        print('early stop!', file=sys.stderr)
                        exit(0)

                    # decay lr, and restore from previously best checkpoint
                    print('load previously best model and decay learning rate to %f%%' %
                          (float(lr_decay)*100), file=sys.stderr)

                    # load model
                    params = torch.load(model_save_path, map_location=lambda storage, loc: storage)
                    model.load_state_dict(params['state_dict'])
                    model = model.to(device)

                    print('restore parameters of the optimizers', file=sys.stderr)
                    optimizer.load_state_dict(torch.load(model_save_path + '.optim'))

                    # set new lr
                    for param_group in optimizer.param_groups:
                        param_group['lr'] *= float(lr_decay)

                    # reset patience
                    patience = 0

"""## **Validation**"""

import numpy as np
    import pickle
    from sklearn.metrics import accuracy_score, matthews_corrcoef, confusion_matrix, \
    f1_score, precision_score, recall_score, roc_auc_score
    import matplotlib
    matplotlib.use('agg')
    from matplotlib import pyplot as plt

def plot_confusion_matrix(y_true, y_pred, classes, normalize=False, title=None, path='cm', cmap=plt.cm.Reds):
    """
    This function prints and plots the confusion matrix.
    Normalization can be applied by setting `normalize=True`.
    """
    if not title:
        if normalize:
            title = 'Normalized confusion matrix'
        else:
            title = 'Confusion matrix, without normalization'

    # Compute confusion matrix
    cm = confusion_matrix(y_true, y_pred)

    if normalize:
        cm = cm.astype('float') / cm.sum(axis=1)[:, np.newaxis]
    pickle.dump(cm, open(path, 'wb'))

    fig, ax = plt.subplots()
    im = ax.imshow(cm, interpolation='nearest', cmap=cmap)
    ax.figure.colorbar(im, ax=ax)
    # We want to show all ticks...
    ax.set(xticks=np.arange(cm.shape[1]),
           yticks=np.arange(cm.shape[0]),
           # ... and label them with the respective list entries
           xticklabels=classes, yticklabels=classes,
           title=title,
           ylabel='True label',
           xlabel='Predicted label')

    # Rotate the tick labels and set their alignment.
    plt.setp(ax.get_xticklabels(), rotation=45, ha="right",
             rotation_mode="anchor")

    # Loop over data dimensions and create text annotations.
    fmt = '.2f' if normalize else 'd'
    thresh = cm.max() / 2.
    for i in range(cm.shape[0]):
        for j in range(cm.shape[1]):
            ax.text(j, i, format(cm[i, j], fmt),
                    ha="center", va="center",
                    color="white" if cm[i, j] > thresh else "black")
    fig.tight_layout()
    return ax

# calculate accuracy

  epoch = 0

  while epoch < int(max_epoch):
    epoch += 1

    model = SentimentClassifierModel.load('/content/gdrive/My Drive/Colab Notebooks/' + prefix + '_model.bin.e' + str(epoch), device)

    model.to(device)

    model.eval()

    df_test = validation_data

    df_test = df_test.sort_values(by='BERT_processed_text_length', ascending=False)

    test_batch_size = 32

    n_batch = int(np.ceil(df_test.shape[0]/test_batch_size))

    cn_loss = torch.load('loss_func', map_location=lambda storage, loc: storage).to(device)

    ProcessedText_BERT = list(df_test.BERT_processed_text)
    InformationType_label = list(df_test.review_rating_label)

    test_loss = 0.
    prediction = []
    prob = []

    softmax = torch.nn.Softmax(dim=1)

    with torch.no_grad():
        for i in range(n_batch):
            sents = ProcessedText_BERT[i*test_batch_size: (i+1)*test_batch_size]
            targets = torch.tensor(InformationType_label[i * test_batch_size: (i + 1) * test_batch_size],
                                   dtype=torch.long, device=device)
            batch_size = len(sents)

            pre_softmax = model(sents)[0]
            batch_loss = cn_loss(pre_softmax, targets)
            test_loss += batch_loss.item()*batch_size
            prob_batch = softmax(pre_softmax)
            prob.append(prob_batch)

            prediction.extend([t.item() for t in list(torch.argmax(prob_batch, dim=1))])

    prob = torch.cat(tuple(prob), dim=0)
    loss = test_loss/df_test.shape[0]

    pickle.dump([label_names[i] for i in prediction], open(prefix+'_test_prediction', 'wb'))
    pickle.dump(prob.data.cpu().numpy(), open(prefix + '_test_prediction_prob', 'wb'))

    accuracy = accuracy_score(df_test.review_rating_label.values, prediction)
    matthews = matthews_corrcoef(df_test.review_rating_label.values, prediction)

    precisions = {}
    recalls = {}
    f1s = {}
    aucrocs = {}

    for i in range(len(label_names)):
        prediction_ = [1 if pred == i else 0 for pred in prediction]
        true_ = [1 if label == i else 0 for label in df_test.review_rating_label.values]
        f1s.update({label_names[i]: f1_score(true_, prediction_)})
        precisions.update({label_names[i]: precision_score(true_, prediction_)})
        recalls.update({label_names[i]: recall_score(true_, prediction_)})
        aucrocs.update({label_names[i]: roc_auc_score(true_, list(t.item() for t in prob[:, i]))})

    metrics_dict = {'loss': loss, 'accuracy': accuracy, 'matthews coef': matthews, 'precision': precisions,
                         'recall': recalls, 'f1': f1s, 'aucroc': aucrocs}

    pickle.dump(metrics_dict, open(prefix+'_evaluation_metrics', 'wb'))

    cm = plot_confusion_matrix(list(df_test.review_rating_label.values), prediction, label_names, normalize=False,
                          path=prefix+'_test_confusion_matrix', title='confusion matrix for test dataset')
    plt.savefig(prefix+'_test_confusion_matrix', format='png')
    cm_norm = plot_confusion_matrix(list(df_test.review_rating_label.values), prediction, label_names, normalize=True,
                          path=prefix+'_test normalized_confusion_matrix', title='normalized confusion matrix for test dataset')
    plt.savefig(prefix+'_test_normalized_confusion_matrix', format='png')

    print('epoch: ' + str(epoch))
    print('accuracy: %.2f' % accuracy)
    print('loss: %.2f' % loss)
    print('matthews coef: %.2f' % matthews)
    print('-' * 80)
    for i in range(len(label_names)):
        print('precision score for %s: %.2f' % (label_names[i], precisions[label_names[i]]))
        print('recall score for %s: %.2f' % (label_names[i], recalls[label_names[i]]))
        print('f1 score for %s: %.2f' % (label_names[i], f1s[label_names[i]]))
        print('auc roc score for %s: %.2f' % (label_names[i], aucrocs[label_names[i]]))
        print('-' * 80)

print(precisions)

precisions

recalls

f1s

aucrocs