import re

file_path = r"c:\xampp\htdocs\Peptide\supabase-schema.sql"

with open(file_path, "r", encoding="utf-8") as file:
    lines = file.readlines()

new_lines = []
# Match CREATE POLICY "policy name" ON table_name
pattern = re.compile(r"^\s*CREATE\s+POLICY\s+\"([^\"]+)\"\s+ON\s+([a-zA-Z0-9_\.]+)", re.IGNORECASE)

for line in lines:
    match = pattern.search(line)
    if match:
        policy_name = match.group(1)
        table_name = match.group(2)
        drop_statement = f"DROP POLICY IF EXISTS \"{policy_name}\" ON {table_name};\n"
        if len(new_lines) == 0 or drop_statement.strip() not in new_lines[-1]:
            new_lines.append(drop_statement)
    new_lines.append(line)

with open(file_path, "w", encoding="utf-8") as file:
    file.writelines(new_lines)

print("Successfully added DROP POLICY statements to make the script idempotent.")
