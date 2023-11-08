import pyspark
from pyspark.sql import SparkSession

from pyspark.ml.feature import VectorAssembler
from pyspark.sql.types import * 
from pyspark.sql.functions import *
from pyspark.ml.feature import StringIndexer
from pyspark.ml.feature import MinMaxScaler

from pyspark.ml.classification import *
from pyspark.ml.evaluation import *
from pyspark.sql.functions import *
from pyspark.ml.tuning import CrossValidator, ParamGridBuilder

import time

# Create SparkSession
spark = SparkSession.builder.appName("Classification").master("local[4]").config("spark.executor.cores", 4).getOrCreate()


# define correct schema
data_schema = [StructField("duration", IntegerType(), True),
StructField("protocol_type", StringType(), True),
StructField("service", StringType(), True),
StructField("flag", StringType(), True),
StructField("src_bytes", IntegerType(), True),
StructField("dst_bytes", IntegerType(), True),
StructField("land", IntegerType(), True),
StructField("wrong_fragment", IntegerType(), True),
StructField("urgent", IntegerType(), True),
StructField("hot", IntegerType(), True),
StructField("num_failed_logins", IntegerType(), True),
StructField("logged_in", IntegerType(), True),
StructField("num_compromised", IntegerType(), True),
StructField("root_shell", IntegerType(), True),
StructField("su_attempted", IntegerType(), True),
StructField("num_root", IntegerType(), True),
StructField("num_file_creations", IntegerType(), True),
StructField("num_shells", IntegerType(), True),
StructField("num_access_files", IntegerType(), True),
StructField("num_outbound_cmds", IntegerType(), True),
StructField("is_host_login", IntegerType(), True),
StructField("is_guest_login", IntegerType(), True),
StructField("count", IntegerType(), True),
StructField("srv_count", IntegerType(), True),
StructField("serror_rate", DoubleType(), True),
StructField("srv_serror_rate", DoubleType(), True),
StructField("rerror_rate", DoubleType(), True),
StructField("srv_rerror_rate", DoubleType(), True),
StructField("same_srv_rate", DoubleType(), True),
StructField("diff_srv_rate", DoubleType(), True),
StructField("srv_diff_host_rate", DoubleType(), True),
StructField("dst_host_count", IntegerType(), True),
StructField("dst_host_srv_count", IntegerType(), True),
StructField("dst_host_same_srv_rate", DoubleType(), True),
StructField("dst_host_diff_srv_rate", DoubleType(), True),
StructField("dst_host_same_src_port_rate", DoubleType(), True),
StructField("dst_host_srv_diff_host_rate", DoubleType(), True),
StructField("dst_host_serror_rate", DoubleType(), True),
StructField("dst_host_srv_serror_rate", DoubleType(), True),
StructField("dst_host_rerror_rate", DoubleType(), True),
StructField("dst_host_srv_rerror_rate", DoubleType(), True),
StructField("category", StringType(), True)]

final_struc = StructType(fields=data_schema)

# Read train and test data
path ="data/"
train_df = spark.read.csv(path+'kddcup.data',schema=final_struc)
test_df = spark.read.csv(path+'corrected',schema=final_struc)


# Convert all attack classes to 4 main attack classes
train_df = train_df.withColumn('category', 
    when(train_df.category == 'back.', 'dos')
    .when(train_df.category == 'buffer_overflow.', 'u2r')
    .when(train_df.category == 'ftp_write.', 'r2l')
    .when(train_df.category == 'guess_passwd.', 'r2l')
    .when(train_df.category == 'imap.', 'r2l')
    .when(train_df.category == 'ipsweep.', 'probe')
    .when(train_df.category == 'land.', 'dos')
    .when(train_df.category == 'loadmodule.', 'u2r')
    .when(train_df.category == 'multihop.', 'r2l')
    .when(train_df.category == 'neptune.', 'dos')
    .when(train_df.category == 'nmap.', 'probe')
    .when(train_df.category == 'perl.', 'u2r')
    .when(train_df.category == 'phf.', 'r2l')
    .when(train_df.category == 'pod.', 'dos')
    .when(train_df.category == 'portsweep.', 'probe')
    .when(train_df.category == 'rootkit.', 'u2r')
    .when(train_df.category == 'satan.', 'probe')
    .when(train_df.category == 'smurf.', 'dos')
    .when(train_df.category == 'spy.', 'r2l')
    .when(train_df.category == 'teardrop.', 'dos')
    .when(train_df.category == 'warezclient.', 'r2l')
    .when(train_df.category == 'warezmaster.', 'r2l')
    .otherwise('normal')
 )

# Convert all attack classes to 4 main attack classes
test_df = test_df.withColumn('category', 
    when(test_df.category == 'back.', 'dos')
    .when(test_df.category == 'buffer_overflow.', 'u2r')
    .when(test_df.category == 'ftp_write.', 'r2l')
    .when(test_df.category == 'guess_passwd.', 'r2l')
    .when(test_df.category == 'imap.', 'r2l')
    .when(test_df.category == 'ipsweep.', 'probe')
    .when(test_df.category == 'land.', 'dos')
    .when(test_df.category == 'loadmodule.', 'u2r')
    .when(test_df.category == 'multihop.', 'r2l')
    .when(test_df.category == 'neptune.', 'dos')
    .when(test_df.category == 'nmap.', 'probe')
    .when(test_df.category == 'perl.', 'u2r')
    .when(test_df.category == 'phf.', 'r2l')
    .when(test_df.category == 'pod.', 'dos')
    .when(test_df.category == 'portsweep.', 'probe')
    .when(test_df.category == 'rootkit.', 'u2r')
    .when(test_df.category == 'satan.', 'probe')
    .when(test_df.category == 'smurf.', 'dos')
    .when(test_df.category == 'spy.', 'r2l')
    .when(test_df.category == 'teardrop.', 'dos')
    .when(test_df.category == 'warezclient.', 'r2l')
    .when(test_df.category == 'warezmaster.', 'r2l')
    .otherwise('normal')
 )

input_columns = train_df.columns[0:-1]
input_columns


# change label (class variable) to string type to prep for reindexing
# Pyspark is expecting a zero indexed integer for the label column. 
# Just in case our data is not in that format... we will treat it by using the StringIndexer built in method
dependent_var = 'category'

train_renamed = train_df.withColumn("label_str", train_df[dependent_var].cast(StringType())) #Rename and change to string type
train_indexer = StringIndexer(inputCol="label_str", outputCol="label") #Pyspark is expecting the this naming convention 
train_indexed = train_indexer.fit(train_renamed).transform(train_renamed)

test_renamed = test_df.withColumn("label_str", test_df[dependent_var].cast(StringType())) #Rename and change to string type
test_indexer = StringIndexer(inputCol="label_str", outputCol="label") #Pyspark is expecting the this naming convention 
test_indexed = test_indexer.fit(test_renamed).transform(test_renamed)


# Convert all string type data in the input column list to numeric
# Otherwise the Algorithm will not be able to process it

# Also we will use these lists later on
train_numeric_inputs = []
train_string_inputs = []
for column in input_columns:
    # First identify the string vars in your input column list
    if str(train_indexed.schema[column].dataType) == 'StringType':
        # Set up your String Indexer function
        train_indexer = StringIndexer(inputCol=column, outputCol=column+"_num") 
        # Then call on the train_indexer you created here
        train_indexed = train_indexer.fit(train_indexed).transform(train_indexed)
        # Rename the column to a new name so you can disinguish it from the original
        new_col_name = column+"_num"
        # Add the new column name to the string inputs list
        train_string_inputs.append(new_col_name)
    else:
        # If no change was needed, take no action 
        # And add the numeric var to the num list
        train_numeric_inputs.append(column)


# Convert all string type data in the input column list to numeric
# Otherwise the Algorithm will not be able to process it

test_numeric_inputs = []
test_string_inputs = []
for column in input_columns:
    # First identify the string vars in your input column list
    if str(test_indexed.schema[column].dataType) == 'StringType':
        # Set up your String Indexer function
        test_indexer = StringIndexer(inputCol=column, outputCol=column+"_num2") 
        # Then call on the test_indexer you created here
        test_indexed = test_indexer.fit(test_indexed).transform(test_indexed)
        # Rename the column to a new name so you can disinguish it from the original
        new_col_name = column+"_num2"
        # Add the new column name to the string inputs list
        test_string_inputs.append(new_col_name)
    else:
        # If no change was needed, take no action 
        # And add the numeric var to the num list
        test_numeric_inputs.append(column)


# Before we correct for negative values that may have been found above, 
# We need to vectorize our df
train_features_list = train_numeric_inputs + train_string_inputs
# Create your vector assembler object
train_assembler = VectorAssembler(inputCols=train_features_list,outputCol='features')
# And call on the vector assembler to transform your dataframe
train_output = train_assembler.transform(train_indexed).select('features','label')


# Before we correct for negative values that may have been found above, 
# We need to vectorize our df
test_features_list = test_numeric_inputs + test_string_inputs
# Create your vector assembler object
test_assembler = VectorAssembler(inputCols=test_features_list,outputCol='features')
# And call on the vector assembler to transform your dataframe
test_output = test_assembler.transform(test_indexed).select('features','label')


# Create the mix max scaler object 
train_scaler = MinMaxScaler(inputCol="features", outputCol="scaledFeatures",min=0,max=1000)
print("Features scaled to range: [%f, %f]" % (train_scaler.getMin(), train_scaler.getMax()))

# Compute summary statistics and generate MinMaxScalerModel
trainScalerModel = train_scaler.fit(train_output)

# rescale each feature to range [min, max].
train_scaled_data = trainScalerModel.transform(train_output)
train_final_data = train_scaled_data.select('label','scaledFeatures')
# Rename to default value
train_final_data = train_final_data.withColumnRenamed("scaledFeatures","features")


# Create the mix max scaler object 
test_scaler = MinMaxScaler(inputCol="features", outputCol="scaledFeatures",min=0,max=1000)
print("Features scaled to range: [%f, %f]" % (test_scaler.getMin(), test_scaler.getMax()))

# Compute summary statistics and generate MinMaxScalerModel
testScalerModel = test_scaler.fit(test_output)

# rescale each feature to range [min, max].
test_scaled_data = testScalerModel.transform(test_output)
test_final_data = test_scaled_data.select('label','scaledFeatures')
# Rename to default value
test_final_data = test_final_data.withColumnRenamed("scaledFeatures","features")


MC_evaluator = MulticlassClassificationEvaluator(metricName="accuracy") # redictionCol="prediction",


start_time = time.time()

classifier = RandomForestClassifier(labelCol='label',featuresCol='features',maxDepth=10,numTrees=10,maxBins=10)

fitModel = classifier.fit(train_final_data)

print("--- %s seconds ---" % (time.time() - start_time))
print(" ")

predictions = fitModel.transform(test_final_data)

accuracy = (MC_evaluator.evaluate(predictions))*100
print(" ")
print("Accuracy: ",accuracy)


from pyspark.mllib.evaluation import MulticlassMetrics
predictionLabels = predictions.select('prediction', 'label')
metrics = MulticlassMetrics(predictionLabels.rdd)


precision = metrics.weightedPrecision
recall = metrics.weightedRecall
f_Measure = metrics.weightedFMeasure()
true_positive_rate = metrics.weightedTruePositiveRate
false_positive_rate = metrics.weightedFalsePositiveRate


print("precision: ", precision)
print("recall: ", recall)
print("f_Measure: ", f_Measure)
print("true_positive_rate: ", true_positive_rate)
print("false_positive_rate: ", false_positive_rate)

