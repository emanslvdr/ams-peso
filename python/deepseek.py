import re
import joblib
import pandas as pd
import torch
from transformers import AutoTokenizer
from extractor import extract_text_from_pdf, extract_text_from_docx
from extractor import (
    extract_skills, extract_experience, extract_education, extract_certifications,
    extract_job_role, extract_recruiter_decision, extract_salary_expectation,
    extract_projects_count, extract_date_of_birth, extract_nationality,
    extract_address, extract_email, extract_phone_number, extract_linkedin_url,
    extract_languages, extract_volunteer_experience, extract_publications, extract_extra_fields
)

# Diagnostic information
print(f"PyTorch version: {torch.__version__}")
print(f"CUDA available: {torch.cuda.is_available()}")
if torch.cuda.is_available():
    print(f"CUDA version: {torch.version.cuda}")
    print(f"GPU: {torch.cuda.get_device_name(0)}")

# Load trained models
rf_model = joblib.load('random_forest_job_segmentation_model.pkl')
xgb_model = joblib.load('xgboost_job_scoring_model.pkl')

# Get expected feature names from the trained models
EXPECTED_FEATURES_RF = rf_model.feature_names_in_
EXPECTED_FEATURES_XGB = xgb_model.feature_names_in_

# Load just the tokenizer from DeepSeek-R1 for embedding/tokenization
# This uses much less memory than loading the full model
MODEL_NAME = "deepseek-ai/DeepSeek-R1-Distill-Qwen-1.5B"
print(f"Loading tokenizer for {MODEL_NAME}...")
tokenizer = AutoTokenizer.from_pretrained(MODEL_NAME)
print("Tokenizer loaded successfully")

# CSV Fields
CSV_FIELDS = [
    "Resume_ID", "Name", "Skills", "Experience (Years)", "Education",
    "Certifications", "Job Role", "Recruiter Decision",
    "Salary Expectation ($)", "Projects Count", "AI Score (0-100)",
    "Date of Birth", "Nationality", "Address", "Email", "Phone Number",
    "LinkedIn URL", "Languages", "Volunteer Experience", "Publications", "Detected Extra Fields", "DeepSeek Token Count"
]
def deepseek_extract(text):
    """
    Uses DeepSeek tokenizer and extraction functions to process resume text.
    This approach doesn't run the full DeepSeek model but still leverages the DeepSeek tokenizer.
    """
    # Use DeepSeek tokenizer to preprocess text
    try:
        # Only tokenize (no model inference)
        # This gives us the benefit of DeepSeek's tokenization without the memory cost
        tokens = tokenizer.encode(text[:1000], truncation=True, max_length=512)
        token_count = len(tokens)
        print(f"Text tokenized with DeepSeek tokenizer: {token_count} tokens")
    except Exception as e:
        print(f"Tokenization error: {e}")
        token_count = 0

    # Extract data using rule-based extraction functions
    extracted_data = {
        "Skills": extract_skills(text),
        "Experience (Years)": extract_experience(text),
        "Education": extract_education(text),
        "Certifications": extract_certifications(text),
        "Job Role": extract_job_role(text),
        "Recruiter Decision": extract_recruiter_decision(text),
        "Salary Expectation ($)": extract_salary_expectation(text),
        "Projects Count": extract_projects_count(text),
        "AI Score (0-100)": "Not implemented",  # Placeholder
        "Date of Birth": extract_date_of_birth(text),
        "Nationality": extract_nationality(text),
        "Address": extract_address(text),
        "Email": extract_email(text),
        "Phone Number": extract_phone_number(text),
        "LinkedIn URL": extract_linkedin_url(text),
        "Languages": extract_languages(text),
        "Volunteer Experience": extract_volunteer_experience(text),
        "Publications": extract_publications(text),
        "Detected Extra Fields": extract_extra_fields(text)
    }

    # Use token count as a feature (DeepSeek's assessment of text complexity)
    # This maintains a connection to DeepSeek in your workflow
    extracted_data["DeepSeek Token Count"] = token_count

    # Predict job role using Random Forest
    extracted_data["Job Role"] = predict_job_role(extracted_data)

    # Predict AI Score using XGBoost
    extracted_data["AI Score (0-100)"] = predict_ai_score(extracted_data)

    return extracted_data

def predict_job_role(extracted_data):
    """Uses Random Forest to predict job role."""
    
    # Initialize feature dictionary with default values (0 for skills not present)
    features = {feature: 0 for feature in EXPECTED_FEATURES_RF}

    # Convert numerical fields safely
    def safe_convert(value, default=0):
        """Convert value to float safely, return default if conversion fails."""
        try:
            return float(value)
        except (ValueError, TypeError):
            return default  # Return 0 if conversion fails

    # Fill in numerical values safely
    features["Experience (Years)"] = safe_convert(extracted_data["Experience (Years)"])
    features["Education"] = safe_convert(extracted_data["Education"])
    features["Certifications"] = safe_convert(extracted_data["Certifications"])
    features["Salary Expectation ($)"] = safe_convert(extracted_data["Salary Expectation ($)"])

    # One-hot encode skills
    skills_list = extracted_data["Skills"].split(", ") if extracted_data["Skills"] else []
    for skill in skills_list:
        if skill in features:
            features[skill] = 1  # Mark skill as present

    # Convert to DataFrame and ensure correct column order
    features_df = pd.DataFrame([features])[EXPECTED_FEATURES_RF]

    return rf_model.predict(features_df)[0]  # Return predicted job role

def predict_ai_score(extracted_data):
    """Uses XGBoost to predict AI job score."""
    
    # Initialize feature dictionary with default values
    features = {feature: 0 for feature in EXPECTED_FEATURES_XGB}

    # Convert numerical fields safely
    def safe_convert(value, default=0):
        """Convert value to float safely, return default if conversion fails."""
        try:
            return float(value)
        except (ValueError, TypeError):
            return default  # Return 0 if conversion fails

    # Fill in numerical values safely
    features["Experience (Years)"] = safe_convert(extracted_data["Experience (Years)"])
    features["Education"] = safe_convert(extracted_data["Education"])
    features["Certifications"] = safe_convert(extracted_data["Certifications"])
    features["Salary Expectation ($)"] = safe_convert(extracted_data["Salary Expectation ($)"])

    # One-hot encode skills
    skills_list = extracted_data["Skills"].split(", ") if extracted_data["Skills"] else []
    for skill in skills_list:
        if skill in features:
            features[skill] = 1  

    # Convert to DataFrame and ensure correct column order
    features_df = pd.DataFrame([features])[EXPECTED_FEATURES_XGB]

    return xgb_model.predict(features_df)[0]  # Return AI job score