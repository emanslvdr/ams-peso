import re
import joblib
import pandas as pd
import torch
from transformers import pipeline
from extractor import extract_text_from_pdf, extract_text_from_docx  # ✅ Only keep used imports

# ✅ Ensure PyTorch is using GPU
if torch.cuda.is_available():
    device = 0  # Use GPU
else:
    device = -1  # Fallback to CPU

# ✅ Load trained models
rf_model = joblib.load('random_forest_job_segmentation_model.pkl')
xgb_model = joblib.load('xgboost_job_scoring_model.pkl')

# ✅ Load DeepSeek model pipeline (Uses GPU if available)
pipe = pipeline("text-generation", model="deepseek-ai/DeepSeek-R1-Distill-Qwen-1.5B", device=device)

# ✅ CSV Fields
CSV_FIELDS = [
    "Resume_ID", "Name", "Skills", "Experience (Years)", "Education",
    "Certifications", "Job Role", "Recruiter Decision",
    "Salary Expectation ($)", "Projects Count", "AI Score (0-100)",
    "Date of Birth", "Nationality", "Address", "Email", "Phone Number",
    "LinkedIn URL", "Languages", "Volunteer Experience", "Publications", "Detected Extra Fields"
]

def deepseek_extract(text):
    """Uses DeepSeek to extract job-related data from text."""
    result = pipe(text, max_length=500)

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

    # ✅ Predict job role using Random Forest
    extracted_data["Job Role"] = predict_job_role(extracted_data)

    # ✅ Predict AI Score using XGBoost
    extracted_data["AI Score (0-100)"] = predict_ai_score(extracted_data)

    return extracted_data

def predict_job_role(extracted_data):
    """Uses Random Forest to predict job role."""
    features = {
        "Experience (Years)": extracted_data["Experience (Years)"],
        "Skills": extracted_data["Skills"],
        "Education": extracted_data["Education"],
        "Certifications": extracted_data["Certifications"],
        "Salary Expectation ($)": extracted_data["Salary Expectation ($)"]
    }
    
    features_df = pd.DataFrame([features])

    # ✅ Handle categorical data
    for col in features_df.select_dtypes(include=["object"]).columns:
        features_df[col] = features_df[col].astype("category").cat.codes

    return rf_model.predict(features_df)[0]

def predict_ai_score(extracted_data):
    """Uses XGBoost to predict AI job score."""
    features = {
        "Experience (Years)": extracted_data["Experience (Years)"],
        "Skills": extracted_data["Skills"],
        "Education": extracted_data["Education"],
        "Certifications": extracted_data["Certifications"],
        "Salary Expectation ($)": extracted_data["Salary Expectation ($)"]
    }
    
    features_df = pd.DataFrame([features])

    # ✅ Handle categorical data for XGBoost
    for col in features_df.select_dtypes(include=["object"]).columns:
        features_df[col] = features_df[col].astype("category").cat.codes

    return xgb_model.predict(features_df)[0]
