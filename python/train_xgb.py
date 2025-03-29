import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import MultiLabelBinarizer, LabelEncoder
from xgboost import XGBRegressor
import joblib
from sklearn.metrics import mean_squared_error

# Load your dataset
df = pd.read_csv("datasets/AI_Resume_Screening.csv")

# Convert "Skills" column from a comma-separated string to a list
df["Skills"] = df["Skills"].apply(lambda x: x.split(", "))

# Use MultiLabelBinarizer to convert skills into numerical format
mlb = MultiLabelBinarizer()
skills_encoded = mlb.fit_transform(df["Skills"])

# Convert back to DataFrame
skills_df = pd.DataFrame(skills_encoded, columns=mlb.classes_)

# Merge the encoded skills back into the dataset
df = df.drop(columns=["Skills"]).join(skills_df)

# Encode categorical columns
for col in ["Education", "Certifications"]:
    le = LabelEncoder()
    df[col] = le.fit_transform(df[col])

# Ensure all columns are numeric
df = df.apply(pd.to_numeric, errors='coerce')

# Features and target for XGBoost (AI score prediction)
features = ['Experience (Years)', 'Education', 'Certifications', 'Salary Expectation ($)'] + list(skills_df.columns)
target_ai_score = 'AI Score (0-100)'

X = df[features]
y_ai_score = df[target_ai_score]

# Split data into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y_ai_score, test_size=0.2, random_state=42)

# Train the XGBoost model
xgb_model = XGBRegressor(objective='reg:squarederror', n_estimators=100, random_state=42)
xgb_model.fit(X_train, y_train)

# Save the model to a file
joblib.dump(xgb_model, 'xgboost_job_scoring_model.pkl')

# Evaluate the model
y_pred = xgb_model.predict(X_test)
mse = mean_squared_error(y_test, y_pred)
print(f"XGBoost Model MSE on Test Data: {mse}")
