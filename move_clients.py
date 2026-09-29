import re

with open('resources/views/components/sidebar-nav.blade.php', 'r') as f:
    content = f.read()

# Extract the clients block
client_block_regex = r"(\s*<a href=\"\{\{ route\('clients'\) \}\}\".*?Clients\n\s*</a>)"
match = re.search(client_block_regex, content, re.DOTALL)

if match:
    client_block = match.group(1)
    # Remove it from the current location
    content = content.replace(client_block, '')
    
    # Insert it after Dashboard block
    dashboard_end_regex = r"(Dashboard\n\s*</a>\n)"
    content = re.sub(dashboard_end_regex, r"\1" + client_block + "\n", content, count=1)
    
    with open('resources/views/components/sidebar-nav.blade.php', 'w') as f:
        f.write(content)
    print("Successfully moved Clients link.")
else:
    print("Could not find Clients block.")
