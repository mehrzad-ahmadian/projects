# Iranian Stock-Market Analysis


* A **crawler** was designed using Telethon library to collect data from signal provider channels in Telegram and save them in MySQL.
* Data was automatically labeled with stock symbols found in message text.
* Data was annotated with buy/sell/neutral labels, as well as sentiment (positive/negative/ neutral) labels.
* Data was visualized using **Microsoft Power BI**.
* **Sentiment Analysis** was done using **Naïve Bayes** (TF-IDF was used for feature extraction) and **BERT** to classify messages into positive/negative/neutral.
* **Classification** was done using **Naïve Bayes** (TF-IDF was used for feature extraction) and **BERT** to classify signal messages into buy/sell/neutral.
