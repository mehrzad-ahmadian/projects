from pyspark.sql import SparkSession
from pyspark.ml.clustering import BisectingKMeans
from pyspark.ml.feature import VectorAssembler
from pyspark.ml.feature import StandardScaler


spark = SparkSession.builder.appName('k-means').getOrCreate()
dataset = spark.read.csv('data/c1.csv',header=True,inferSchema=True)
feat_cols = ['col1', 'col2']

assembler = VectorAssembler(inputCols=feat_cols,outputCol='features')

final_data = assembler.transform(dataset)

scaler = StandardScaler(inputCol='features',outputCol='scaledFeatures')

scaler_model = scaler.fit(final_data)
cluster_final_data = scaler_model.transform(final_data)

wcss = []
for i in range(2,25):
    kmeans = BisectingKMeans(featuresCol='scaledFeatures',k=i)
    model = kmeans.fit(cluster_final_data)
    wcss.append(model.summary.trainingCost)

import pylab as plt
plt.plot(range(2, 25), wcss)
plt.title('The Elbow Method for c1 file (bisecting-k-means)')
plt.xlabel('Number of clusters')
plt.ylabel('WCSS')
plt.show()
