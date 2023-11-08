from pyspark import SparkContext
context = SparkContext()
context.addPyFile('graphframes-0.8.1-spark3.0-s_2.12.jar')

from pyspark.sql.session import SparkSession
spark = SparkSession(context)

from pyspark.sql.types import *
from graphframes import *
spark = SparkSession.builder.appName("mehrzadHealthcareFraudDetector").getOrCreate()


path ="medicare/"
# read data file
file = spark.read.csv(path+'Medicare_Provider_Util_Payment_PUF_CY2012.csv',inferSchema=True,header=True)


from pyspark.sql.functions import col
rawData = file.select("NPI", "PROVIDER_TYPE", "HCPCS_CODE").limit(50000)
data = rawData.select([col(c).cast("string") for c in rawData.columns])


specialities = data.select("PROVIDER_TYPE").distinct()
specialitiesList = [row['PROVIDER_TYPE'] for row in specialities.collect()]


from pyspark.sql.functions import lit
npis = data.selectExpr("NPI as id", "NPI as name", "PROVIDER_TYPE as speciality").distinct()
npis = npis.withColumn("type", lit("npi"))


hcpcsCodes = data.selectExpr("HCPCS_CODE as id", "HCPCS_CODE as name").distinct()
hcpcsCodes = hcpcsCodes.withColumn("speciality", lit(""))
hcpcsCodes = hcpcsCodes.withColumn("type", lit("hcpcs"))


# Vertex DataFrame
v = npis.union(hcpcsCodes)

# Edge DataFrame
e = data.selectExpr("NPI as src", "HCPCS_CODE as dst").distinct()

# Create a GraphFrame
g = GraphFrame(v, e)


import statistics

for speciality in specialitiesList:
    print(speciality)
    sourceIds = data.where(data.PROVIDER_TYPE == speciality).selectExpr("NPI as id").distinct()
    sourceIdsList = [row['id'] for row in sourceIds.collect()]
    # Run PageRank personalized for vertex ["a", "b", "c", "d"] in parallel
    pageRankResults = g.parallelPersonalizedPageRank(resetProbability=0.15, sourceIds=sourceIdsList, maxIter=10)
    results = pageRankResults.vertices.where(pageRankResults.vertices.type == "npi").where(pageRankResults.vertices.speciality != speciality).select("id", "name", "pageranks")
    finalResults = results.toPandas()
    output = []
    for index, row in finalResults.iterrows():
        ranksList = list([float(x) for x in row['pageranks']])
        output.append([row['id'], row['name'], statistics.mean(ranksList)])
    finalDf = spark.createDataFrame(output, ["id", "NPI", "pagerank"])
    topTenNpis = finalDf.sort(col("pagerank").desc()).limit(5)
    topTenNpis.show()
