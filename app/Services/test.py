import joblib
import numpy as np
import pandas as pd
from sklearn.metrics.pairwise import cosine_similarity
from sklearn.feature_extraction.text import CountVectorizer
import sys

# Load the saved model
model = joblib.load('model.joblib')

# Define the dataset with 3 columns: id, name, description

dataset = pd.read_csv(r'C:\My file\majorProject\datacomponent.csv') #sys.argv[2]

# Define the user-item interaction matrix with 1s and 0s
user_item_ids =[1,2,3]  # sys.argv[1] item ids that the user has interacted with
num_items = len(user_item_ids)

user_item_matrix = np.zeros((1, num_items))
for i, item_id in enumerate(user_item_ids):
    user_item_matrix[0, i] = 1

# Get the indices of the items that the user has interacted with
user_item_ids = np.where(user_item_matrix == 1)[1]

# Get the features of the items that the user has interacted with
user_item_features = np.array(dataset.loc[dataset['id'].isin(user_item_ids)][['name', 'description']].values)

# Get the features of all the items
all_item_features = np.array(dataset[['name', 'description']].values)

# Convert the features to a matrix of token counts
count = CountVectorizer().fit_transform(np.concatenate((user_item_features[:,0], all_item_features[:,0])))

# Compute the cosine similarity between the user-item features and all the item features
cosine_sim = cosine_similarity(count)

# Get the top 3 recommendations for the user
top_recommendations = np.argsort(-cosine_sim[0])[:3]

# Print the top 3 recommended items
print("Top 3 recommended items for user A:")
print(dataset.loc[top_recommendations]['name'].values)

