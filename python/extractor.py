import os
import re

import docx
import csv
import pdfplumber

def extract_from_directory(directory_path):
    """Recursively extract all file paths in the given directory and subdirectories."""
    all_files = []
    for root, dirs, files in os.walk(directory_path):
        for file in files:
            all_files.append(os.path.join(root, file))
    return all_files

def extract_text_from_pdf(file_path):
    try:
        with pdfplumber.open(file_path) as pdf:
            text = ""
            for page in pdf.pages:
                page_text = page.extract_text() or ""
                if page_text:
                    text += page_text + "\n"  # Keep the text from each page separated
                else:
                    print(f"Warning: No text extracted from a page in {file_path}.")
            return text
    except Exception as e:
        print(f"Error extracting text from PDF {file_path}: {e}")
        return ""

def extract_text_from_docx(file_path):
    """Extract text from a DOCX file."""
    doc = docx.Document(file_path)
    text = "\n".join([para.text for para in doc.paragraphs])
    return text.strip()

# def extract_file(file_path):
  #  """Extract rows from CSV file."""
  #  rows = []
  #  with open(file_path, mode='r', newline='', encoding='utf-8') as file:
  #      reader = csv.DictReader(file)
  #      for row in reader:
  #          rows.append(row)
  #  return rows

# -------------- REAL EXTRACTION FUNCTIONS -----------------

def extract_skills(text):
    skill_keywords = [
        "python", "java", "javascript", "html", "css", "sql", "c++", "php",
        "excel", "powerpoint", "react", "node", "laravel", "vue", "flask"
    ]
    found_skills = set()
    for keyword in skill_keywords:
        if re.search(rf"\b{keyword}\b", text, re.IGNORECASE):
            found_skills.add(keyword.lower())
    return ", ".join(sorted(found_skills))

def extract_experience(text):
    match = re.search(r"(\d+)\+?\s+(?:years|yrs)\s+of\s+experience", text, re.IGNORECASE)
    if match:
        return match.group(1)
    return "0"

def extract_education(text):
    edu_levels = {
        "high school": 1,
        "senior high": 2,
        "associate": 2,
        "bachelor": 3,
        "college": 3,
        "undergraduate": 3,
        "master": 4,
        "postgraduate": 4,
        "doctorate": 5,
        "phd": 5
    }
    for level, value in edu_levels.items():
        if level in text.lower():
            return str(value)
    return "0"

def extract_certifications(text):
    matches = re.findall(r"(certification|certified|certificate)", text, re.IGNORECASE)
    return str(len(matches)) if matches else "0"

def extract_job_role(text):
    roles = ["developer", "designer", "data analyst", "data scientist", "engineer", "manager", "intern", "tester"]
    for role in roles:
        if role in text.lower():
            return role
    return "unknown"

def extract_recruiter_decision(text):
    return "Proceed" if "reliable" in text.lower() or "hardworking" in text.lower() else "Review"

def extract_salary_expectation(text):
    match = re.search(r"\$?(\d{4,6})\s*(?:php|usd)?", text, re.IGNORECASE)
    return match.group(1) if match else "0"

def extract_projects_count(text):
    matches = re.findall(r"\b(project[s]?|developed|built|created)\b", text, re.IGNORECASE)
    return str(len(matches)) if matches else "0"

def extract_date_of_birth(text):
    match = re.search(r"(?:birth\s*date|date\s*of\s*birth|dob)[^\d]*(\d{2,4}[/-]\d{2}[/-]\d{2,4})", text, re.IGNORECASE)
    return match.group(1) if match else "Not found"

def extract_nationality(text):
    match = re.search(r"\bnationality[:\- ]+\s*(\w+)", text, re.IGNORECASE)
    return match.group(1) if match else "Not found"

def extract_address(text):
    match = re.search(r"(address[:\- ]+)([^\n]+)", text, re.IGNORECASE)
    return match.group(2).strip() if match else "Not found"

def extract_email(text):
    match = re.search(r"[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+", text)
    return match.group(0) if match else "Not found"

def extract_phone_number(text):
    # Example regex pattern for extracting phone numbers (adjust as needed)
    phone_number_pattern = r"\+?\(?\d{1,4}\)?[-.\s]?\(?\d{1,4}\)?[-.\s]?\d{1,4}[-.\s]?\d{1,4}"

    match = re.search(phone_number_pattern, text)

    if match:
        return "".join(match.groups()) if match.groups() else "Not found"
    return "Not found"

def extract_linkedin_url(text):
    match = re.search(r"https?://(www\.)?linkedin\.com/in/[a-zA-Z0-9_-]+", text)
    return match.group(0) if match else "Not found"

def extract_languages(text):
    languages = ["english", "filipino", "tagalog", "cebuano", "mandarin", "japanese", "spanish"]
    found = [lang for lang in languages if lang in text.lower()]
    return ", ".join(sorted(set(found)))

def extract_volunteer_experience(text):
    if "volunteer" in text.lower():
        return "Yes"
    return "No"

def extract_publications(text):
    match = re.findall(r"(published|publication|research paper)", text, re.IGNORECASE)
    return str(len(match)) if match else "0"

def extract_extra_fields(text):
    extras = []
    if "passport" in text.lower():
        extras.append("Has Passport")
    if "driver" in text.lower():
        extras.append("Has Driver's License")
    return ", ".join(extras) if extras else "None"
