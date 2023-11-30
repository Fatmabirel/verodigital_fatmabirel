<?php
$curl = curl_init(); // Init curl 

curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.baubuddy.de/index.php/login", //login page
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => '{"username":"365", "password":"1"}', // username and password info for login process
    CURLOPT_HTTPHEADER => [
        "Authorization: Basic QVBJX0V4cGxvcmVyOjEyMzQ1NmlzQUxhbWVQYXNz",
        "Content-Type: application/json"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "Curl error: " . $err;
} else {
    $json = json_decode($response, true);
    $accessToken = $json["oauth"]["access_token"];
    // Decode json
}

// Step 2: After Authorization process is completed, init curl again and fetch data from the URL

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.baubuddy.de/dev/index.php/v1/tasks/select",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $accessToken",
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "Curl error: " . $err;
} else {
    $data = json_decode($response, true); // Now I fetch data from the URL and I can use it everywhere 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VERO Digital - Fatma Birel</title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        #searchInput {
            padding: 10px;
            margin-top: 10px;
        }

        button {
            padding: 10px;
            margin-top: 10px;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        #imageInput {
            margin-top: 10px;
        }

        #selectedImage {
            max-width: 100%;
            max-height: 400px;
            margin-top: 10px;
        }
        /*  COLOR BUTTON */
        button {
            padding: 10px;
            margin-top: 10px;
            background-color: green;
            color: white;
            border: none;
            cursor: pointer;
        }


        #searchInput {
            padding: 10px;
            margin-top: 10px;
            width: 300px; 
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* CSS for File Input */
        #fileInputLabel {
            padding: 10px;
            margin-top: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
            display: block;
        }

        #fileInputLabel:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <table id="dataTable" border="1">
        <tr>
            <th>Task</th>
            <th>Title</th>
            <th>Description</th>
            <th>Color Code</th>
        </tr>
    </table>

    <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search data">

    <button onclick="openModal()">Open Image Modal</button>

    <!-- Create Modal for selecting an image -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <label for="imageInput" id="fileInputLabel">Select File</label>
        <img id="selectedImage" src="#" alt="Selected Image">
    </div>
</div>

    <script>
        // Fill the table with the data
        const data = <?php echo json_encode($data); ?>;
        const table = document.getElementById("dataTable");

        data.forEach(item => {
            const row = table.insertRow();
            row.insertCell(0).innerHTML = item.task;
            row.insertCell(1).innerHTML = item.title;
            row.insertCell(2).innerHTML = item.description;
            const colorCell = row.insertCell(3);
            colorCell.innerHTML = item.colorCode;
            colorCell.style.backgroundColor = `#${item.colorCode}`;
        });

        // JavaScript code for searching the data
        function searchTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toUpperCase();
            const rows = table.getElementsByTagName("tr");

            for (let i = 0; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName("td");
                let found = false;

                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent || cells[j].innerText;

                    if (cellText.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }

                rows[i].style.display = found ? "" : "none";
            }
        }

        // JS code for refresh
        function fetchData() {
            table.innerHTML = "";

            data.forEach(item => {
                const row = table.insertRow();
                row.insertCell(0).innerHTML = item.task;
                row.insertCell(1).innerHTML = item.title;
                row.insertCell(2).innerHTML = item.description;
                const colorCell = row.insertCell(3);
                colorCell.innerHTML = item.colorCode;
                colorCell.style.backgroundColor = `#${item.colorCode}`;
            });
        }

        // Fetch data for 60 minutes and update the table
        setInterval(fetchData, 60 * 60 * 1000);

        // Model settings
        function openModal() {
            document.getElementById("myModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        function displayImage() {
            const input = document.getElementById("imageInput");
            const img = document.getElementById("selectedImage");
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function (e) {
                img.src = e.target.result;
            };

            reader.readAsDataURL(file);
        }
    </script>

</body>
</html>
