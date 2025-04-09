import uuid
import csv
from extractor import extract_from_directory, extract_text_from_pdf, extract_text_from_docx
from deepseek import deepseek_extract, CSV_FIELDS

OUTPUT_CSV = "resume_data.csv"

def process_pdf_or_docx(file_path):
    if file_path.endswith(".pdf"):
        text = extract_text_from_pdf(file_path)
    elif file_path.endswith(".docx"):
        text = extract_text_from_docx(file_path)
    
    if text:
        resume_id = str(uuid.uuid4())
        data = deepseek_extract(text)
        data["Resume_ID"] = resume_id
        return data
    return None

#def process_csv(file_path):
 #   rows = extract_file(file_path)
   # processed_rows = []
  #  for row in rows:
   #     resume_id = str(uuid.uuid4())
   #     combined_row = ", ".join([f"{k}: {v}" for k, v in row.items()])
  #      extracted = deepseek_extract(combined_row)
   #     extracted["Resume_ID"] = resume_id
  #      processed_rows.append(extracted)
  #  return processed_rows

def batch_process():
    all_data = []
    # Loop through the directory and subdirectories to find files
    for file_path in extract_from_directory("datasets"):
        if file_path.endswith(('.pdf', '.docx')):
            data = process_pdf_or_docx(file_path)
            if data:
                all_data.append(data)
       # elif file_path.endswith('.csv'):
       #     data_rows = process_csv(file_path)
       #     all_data.extend(data_rows)

    # Export the data to CSV if any data exists
    if all_data:
        with open(OUTPUT_CSV, mode='w', newline='', encoding='utf-8') as file:
            writer = csv.DictWriter(file, fieldnames=CSV_FIELDS)
            writer.writeheader()
            writer.writerows(all_data)
        print(f"Exported {len(all_data)} entries to {OUTPUT_CSV}")

if __name__ == "__main__":
    batch_process()
