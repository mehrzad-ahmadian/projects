import pandas as pd
from sklearn.metrics import accuracy_score
from sklearn.model_selection import train_test_split
from sklearn.neural_network import MLPClassifier


data = pd.read_csv('data/fashion-mnist_train.csv')
testData = pd.read_csv('data/fashion-mnist_test.csv')

xData = data.iloc[:, 1:].to_numpy()
yData = data['label'].to_numpy()

testX = testData.iloc[:, 1:].to_numpy()
testY = testData['label'].to_numpy()

trainX, validationX, trainY, validationY = train_test_split(xData, yData, test_size=0.25)



trainAccuracies = []
validationAccuracies = []
testAccuracies = []

classifier = MLPClassifier(hidden_layer_sizes=(50,)).fit(trainX, trainY)
trainAccuracies.append(accuracy_score(trainY, classifier.predict(trainX)))
validationAccuracies.append(accuracy_score(validationY, classifier.predict(validationX)))
testAccuracies.append(accuracy_score(testY, classifier.predict(testX)))

print(trainAccuracies)
print(validationAccuracies)
print(testAccuracies)

trainAccuracies = []
validationAccuracies = []
testAccuracies = []



classifier = MLPClassifier(hidden_layer_sizes=(50,)).fit(trainX, trainY)
trainAccuracies.append(accuracy_score(trainY, classifier.predict(trainX)))
validationAccuracies.append(accuracy_score(validationY, classifier.predict(validationX)))
testAccuracies.append(accuracy_score(testY, classifier.predict(testX)))

classifier = MLPClassifier(hidden_layer_sizes=(50,50,)).fit(trainX, trainY)
trainAccuracies.append(accuracy_score(trainY, classifier.predict(trainX)))
validationAccuracies.append(accuracy_score(validationY, classifier.predict(validationX)))
testAccuracies.append(accuracy_score(testY, classifier.predict(testX)))

classifier = MLPClassifier(hidden_layer_sizes=(50,50,50,)).fit(trainX, trainY)
trainAccuracies.append(accuracy_score(trainY, classifier.predict(trainX)))
validationAccuracies.append(accuracy_score(validationY, classifier.predict(validationX)))
testAccuracies.append(accuracy_score(testY, classifier.predict(testX)))

classifier = MLPClassifier(hidden_layer_sizes=(50,50,50,50,)).fit(trainX, trainY)
trainAccuracies.append(accuracy_score(trainY, classifier.predict(trainX)))
validationAccuracies.append(accuracy_score(validationY, classifier.predict(validationX)))
testAccuracies.append(accuracy_score(testY, classifier.predict(testX)))

print(trainAccuracies)
print(validationAccuracies)
print(testAccuracies)

import matplotlib.pyplot as plt
plt.figure(figsize=(8, 6))
plt.plot(range(1, 5), trainAccuracies, label='Train')
plt.plot(range(1, 5), validationAccuracies, label='Validation')
plt.plot(range(1, 5), testAccuracies, label='Test')

plt.xticks(range(1, 5))
plt.xlabel('Hidden Layer Count')
plt.ylabel('Accuracy')
plt.legend()

trainAccuracies = []
validationAccuracies = []
testAccuracies = []

classifier = MLPClassifier(hidden_layer_sizes=(50,)).fit(trainX, trainY)
trainAccuracies.append(accuracy_score(trainY, classifier.predict(trainX)))
validationAccuracies.append(accuracy_score(validationY, classifier.predict(validationX)))
testAccuracies.append(accuracy_score(testY, classifier.predict(testX)))

classifier = MLPClassifier(hidden_layer_sizes=(100,)).fit(trainX, trainY)
trainAccuracies.append(accuracy_score(trainY, classifier.predict(trainX)))
validationAccuracies.append(accuracy_score(validationY, classifier.predict(validationX)))
testAccuracies.append(accuracy_score(testY, classifier.predict(testX)))

classifier = MLPClassifier(hidden_layer_sizes=(150,)).fit(trainX, trainY)
trainAccuracies.append(accuracy_score(trainY, classifier.predict(trainX)))
validationAccuracies.append(accuracy_score(validationY, classifier.predict(validationX)))
testAccuracies.append(accuracy_score(testY, classifier.predict(testX)))

print(trainAccuracies)
print(validationAccuracies)
print(testAccuracies)

plt.figure(figsize=(8, 6))
plt.plot([50, 100, 150], trainAccuracies, label='Train')
plt.plot([50, 100, 150], validationAccuracies, label='Validation')
plt.plot([50, 100, 150], testAccuracies, label='Test')

plt.xticks([50, 100, 150])
plt.xlabel('Hidden Neuron Count')
plt.ylabel('Accuracy')
plt.legend()

trainAccuracies = []
validationAccuracies = []
testAccuracies = []

clf_sgd = MLPClassifier(hidden_layer_sizes=(150,), solver='adam').fit(trainX, trainY)
trainAccuracies.append(accuracy_score(trainY, clf_sgd.predict(trainX)))
validationAccuracies.append(accuracy_score(validationY, clf_sgd.predict(validationX)))
testAccuracies.append(accuracy_score(testY, clf_sgd.predict(testX)))

clf_sgd = MLPClassifier(hidden_layer_sizes=(150,), solver='sgd').fit(trainX, trainY)
trainAccuracies.append(accuracy_score(trainY, clf_sgd.predict(trainX)))
validationAccuracies.append(accuracy_score(validationY, clf_sgd.predict(validationX)))
testAccuracies.append(accuracy_score(testY, clf_sgd.predict(testX)))

clf_lbfgs = MLPClassifier(hidden_layer_sizes=(150,), solver='lbfgs').fit(trainX, trainY)
trainAccuracies.append(accuracy_score(trainY, clf_lbfgs.predict(trainX)))
validationAccuracies.append(accuracy_score(validationY, clf_lbfgs.predict(validationX)))
testAccuracies.append(accuracy_score(testY, clf_lbfgs.predict(testX)))

print(trainAccuracies)
print(validationAccuracies)
print(testAccuracies)

plt.figure(figsize=(8, 6))

plt.plot(['adam', 'sgd', 'lbfgs'], trainAccuracies, label='Train')
plt.plot(['adam', 'sgd', 'lbfgs'], validationAccuracies, label='Validation')
plt.plot(['adam', 'sgd', 'lbfgs'], testAccuracies, label='Test')

plt.xlabel('Solver')
plt.ylabel('Accuracy')

trainAccuracies = []
validationAccuracies = []
testAccuracies = []

for learningRate in [0.001, 0.01, 0.1, 0.5, 0.9]:
  classifier = MLPClassifier(hidden_layer_sizes=(150,), solver='adam', learning_rate_init=learningRate).fit(trainX, trainY)
  trainAccuracies.append(accuracy_score(trainY, classifier.predict(trainX)))
  validationAccuracies.append(accuracy_score(validationY, classifier.predict(validationX)))
  testAccuracies.append(accuracy_score(testY, classifier.predict(testX)))

print(trainAccuracies)
print(validationAccuracies)
print(testAccuracies)

plt.figure(figsize=(8, 6))
plt.plot([0.001, 0.01, 0.1, 0.5, 0.9], trainAccuracies, label='Train')
plt.plot([0.001, 0.01, 0.1, 0.5, 0.9], validationAccuracies, label='Validation')
plt.plot([0.001, 0.01, 0.1, 0.5, 0.9], testAccuracies, label='Test')

plt.xticks([0.001, 0.01, 0.1, 0.5, 0.9])
plt.xlabel('Learning Rate')
plt.ylabel('Accuracy')
plt.legend()