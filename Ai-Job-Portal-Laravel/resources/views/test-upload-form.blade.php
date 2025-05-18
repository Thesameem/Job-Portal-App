<!DOCTYPE html>
<html>
<head>
    <title>Test File Upload</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="file"] {
            display: block;
            margin-bottom: 10px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        #result {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        #preview {
            max-width: 300px;
            margin-top: 20px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <h1>Test File Upload</h1>
    
    <form id="uploadForm" enctype="multipart/form-data">
        <div class="form-group">
            <label for="testFile">Select a file:</label>
            <input type="file" id="testFile" name="test_file">
        </div>
        
        <button type="submit">Upload</button>
    </form>
    
    <div id="result" style="display: none;"></div>
    <img id="preview" style="display: none;">
    
    <script>
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('testFile');
            const resultDiv = document.getElementById('result');
            const preview = document.getElementById('preview');
            
            if (!fileInput.files.length) {
                resultDiv.innerHTML = 'Please select a file to upload';
                resultDiv.style.display = 'block';
                return;
            }
            
            const file = fileInput.files[0];
            const formData = new FormData();
            formData.append('test_file', file);
            
            resultDiv.innerHTML = 'Uploading...';
            resultDiv.style.display = 'block';
            preview.style.display = 'none';
            
            try {
                const response = await fetch('/api/test-upload', {
                    method: 'POST',
                    body: formData,
                });
                
                const result = await response.json();
                
                if (result.error) {
                    resultDiv.innerHTML = `<strong>Error:</strong> ${result.message}`;
                } else {
                    resultDiv.innerHTML = `
                        <strong>Success!</strong><br>
                        Original filename: ${result.details.original_name}<br>
                        Stored path: ${result.details.stored_path}<br>
                        <a href="${result.details.url}" target="_blank">View file</a>
                    `;
                    
                    // If it's an image, show preview
                    if (file.type.startsWith('image/')) {
                        preview.src = result.details.url;
                        preview.style.display = 'block';
                    }
                }
            } catch (error) {
                resultDiv.innerHTML = `<strong>Error:</strong> ${error.message}`;
            }
        });
    </script>
</body>
</html> 