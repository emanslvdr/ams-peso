import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
import joblib
from sklearn.preprocessing import MultiLabelBinarizer, LabelEncoder

# Load your dataset
df = pd.read_csv("datasets/AI_Resume_Screening.csv")

# Ensure Salary Expectation is numeric
df["Salary Expectation ($)"] = pd.to_numeric(df["Salary Expectation ($)"], errors='coerce')

# Convert "Skills" column from a comma-separated string to a list
df["Skills"] = df["Skills"].apply(lambda x: x.split(", "))

# Use MultiLabelBinarizer to convert skills into numerical format
mlb = MultiLabelBinarizer()
skills_encoded = mlb.fit_transform(df["Skills"])

# Convert back to DataFrame
skills_df = pd.DataFrame(skills_encoded, columns=mlb.classes_)

# Merge the encoded skills back into the dataset
df = df.drop(columns=["Skills"]).join(skills_df)

# Convert categorical columns to numerical labels
for col in ["Education", "Certifications", "Job Role"]:
    le = LabelEncoder()
    df[col] = le.fit_transform(df[col])

# Define features and target for Random Forest (job role prediction)
encoded_skills_cols = skills_df.columns.to_list()
features = ["Experience (Years)", "Education", "Certifications", "Salary Expectation ($)"] + encoded_skills_cols
target_job_role = "Job Role"

X = df[features]
y_job_role = df[target_job_role]

# Split data into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y_job_role, test_size=0.2, random_state=42)

# Train the Random Forest model
rf_model = RandomForestClassifier(n_estimators=100, random_state=42)
rf_model.fit(X_train, y_train)

# Save the model to a file
joblib.dump(rf_model, 'random_forest_job_segmentation_model.pkl')

# Evaluate the model
print("Random Forest Model Accuracy on Test Data:", rf_model.score(X_test, y_test))
