import os
import re

exclude_files = [
    r"user\proses_kuesioner.php",
    r"user\hasil.php",
    r"admin\knowledge_base.php"
]

def remove_comments(content, ext):
    if ext in ['.php', '.js', '.css']:
        # Remove multiline comments /* ... */
        content = re.sub(r'/\*[\s\S]*?\*/', '', content)
        # Remove single line comments // ...
        # Be careful not to match URLs like http://
        content = re.sub(r'(?<!:)\/\/.*', '', content)
        
    if ext == '.php':
        # Remove # ... comments (but not # within strings)
        # This is a bit risky but usually okay for simple scripts
        content = re.sub(r'(?<![\'"])\s#.*', '', content)
        
    # Remove HTML comments <!-- ... -->
    content = re.sub(r'<!--[\s\S]*?-->', '', content)
    
    return content

root_dir = r"c:\xampp\htdocs\desadroid\psikologi"

for root, dirs, files in os.walk(root_dir):
    for file in files:
        if file.endswith(('.php', '.js', '.css', '.html')):
            file_path = os.path.join(root, file)
            rel_path = os.path.relpath(file_path, root_dir)
            
            should_exclude = False
            for exc in exclude_files:
                if rel_path.lower() == exc.lower():
                    should_exclude = True
                    break
            
            if should_exclude:
                print(f"Skipping: {rel_path}")
                continue
                
            print(f"Processing: {rel_path}")
            try:
                with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
                    content = f.read()
                
                clean_content = remove_comments(content, os.path.splitext(file)[1])
                
                # Optionally remove extra empty lines created by comment removal
                clean_content = re.sub(r'\n\s*\n', '\n\n', clean_content)
                
                with open(file_path, 'w', encoding='utf-8') as f:
                    f.write(clean_content)
            except Exception as e:
                print(f"Error processing {rel_path}: {e}")

print("Done!")
