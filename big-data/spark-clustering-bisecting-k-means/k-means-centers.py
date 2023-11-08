from pyspark.sql import SparkSession
from pyspark.ml.clustering import KMeans
from pyspark.ml.feature import VectorAssembler
from pyspark.ml.feature import StandardScaler


spark = SparkSession.builder.appName('k-means-centers').getOrCreate()
dataset = spark.read.csv('data/c1.csv',header=True,inferSchema=True)
feat_cols = ['col1', 'col2']

assembler = VectorAssembler(inputCols=feat_cols,outputCol='features')

final_data = assembler.transform(dataset)

scaler = StandardScaler(inputCol='features',outputCol='scaledFeatures')

scaler_model = scaler.fit(final_data)
cluster_final_data = scaler_model.transform(final_data)

kmeans = KMeans(featuresCol='scaledFeatures',k=13)
model = kmeans.fit(cluster_final_data)

# Shows the result.
centers = model.clusterCenters()
print("Cluster Centers: ")
for center in centers:
    print(center)