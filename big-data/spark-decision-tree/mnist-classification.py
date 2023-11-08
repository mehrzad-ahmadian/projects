import time

from pyspark.ml import Pipeline
from pyspark.ml.classification import DecisionTreeClassifier
from pyspark.ml.evaluation import MulticlassClassificationEvaluator
from pyspark.ml.feature import StringIndexer
from pyspark.ml.feature import VectorAssembler
from pyspark.ml.tuning import CrossValidator, ParamGridBuilder
from pyspark.sql import SparkSession

spark = SparkSession.builder.appName("Classification").master("local[4]").config("spark.executor.cores", 4).getOrCreate()

path ="data/"
train_mnist = spark.read.csv(path+'mnist_train.csv',inferSchema=True)
test_mnist = spark.read.csv(path+'mnist_test.csv',inferSchema=True)


# assemble those features to a vector to consume in Spark
train_assembler = VectorAssembler(
    inputCols=train_mnist.columns[1:785],
    outputCol="features")

# assemble those features to a vector to consume in Spark
test_assembler = VectorAssembler(
    inputCols=test_mnist.columns[1:785],
    outputCol="features")


# Transform pixel0,pixel1...pixel783 to one column named "features"
train_final_data = train_assembler.transform(train_mnist).select("_c0", "features").withColumnRenamed("_c0","label")

# Transform pixel0,pixel1...pixel783 to one column named "features"
test_final_data = test_assembler.transform(test_mnist).select("_c0", "features").withColumnRenamed("_c0","label")


# Bin_evaluator = BinaryClassificationEvaluator() #labelCol='label'
MC_evaluator = MulticlassClassificationEvaluator(metricName="accuracy") # redictionCol="prediction",


start_time = time.time()

# Add parameters of your choice here:
classifier = DecisionTreeClassifier(labelCol='label',featuresCol='features',maxDepth=8,maxBins=16)

fitModel = classifier.fit(train_final_data)

print("--- %s seconds ---" % (time.time() - start_time))
print(" ")

predictions = fitModel.transform(test_final_data)

accuracy = (MC_evaluator.evaluate(predictions))*100
print(" ")
print("Accuracy: ",accuracy)



