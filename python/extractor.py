import os
import PyPDF2
import docx
import csv

def extract_from_directory(directory_path):
    """
    Recursively extract all file paths in the given directory and subdirectories.
    """
    all_files = []
    for root, dirs, files in os.walk(directory_path):
        for file in files:
            all_files.append(os.path.join(root, file))
    return all_files

def extract_text_from_pdf(file_path):
    """
    Extract text from a PDF file.
    """
    with open(file_path, "rb") as file:
        reader = PyPDF2.PdfReader(file)
        text = ""
        for page in reader.pages:
            text += page.extract_text()
    return text

def extract_text_from_docx(file_path):
    """
    Extract text from a DOCX file.
    """
    doc = docx.Document(file_path)
    text = ""
    for para in doc.paragraphs:
        text += para.text
    return text

def extract_file(file_path):
    """
    Extract rows from CSV file.
    """
    rows = []
    with open(file_path, mode='r', newline='', encoding='utf-8') as file:
        reader = csv.DictReader(file)
        for row in reader:
            rows.append(row)
    return rows
