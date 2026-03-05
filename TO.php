<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Order</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --accent-color: #3498db;
            --bg-color: #f4f7f6;
            --card-bg: #ffffff;
            --border-color: #dcdde1;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 40px 20px;
            background-color: var(--bg-color);
            display: flex;
            justify-content: center;
        }

        .box {
            width: 100%;
            max-width: 850px;
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            padding: 40px;
        }

        .card_title {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 1.8rem;
        }

        .form-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: 30px;
            row-gap: 20px;
        }

        .full-width {
            grid-column: span 2;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary-color);
            text-transform: uppercase;
        }

        input, select {
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #fafafa;
        }

        input:focus {
            outline: none;
            border-color: var(--accent-color);
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .button-group {
            margin-top: 5px;
            display: flex;
            gap: 10px;
        }

        button {
            background-color: var(--accent-color);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 600;
            transition: background 0.2s;
        }

        button:hover {
            background-color: #2980b9;
        }

        hr {
            grid-column: span 2;
            border: 0;
            border-top: 1px solid #eee;
            margin: 10px 0;
        }

        .modal-overlay {
            display: none; 
            position: fixed; 
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 1000; 
            align-items: center; 
            justify-content: center;
        }

        .popup-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        
        .modal-overlay.active { 
            display: flex;
        }

    </style>
</head>
<body>

    <div class="box">
        <a href="../TO_MGB/index.php">back</a>
        <h1 class="card_title">Travel Order</h1>
        
       <form method="post" id="travelForm" enctype="multipart/form-data">
            <div class="form-container">
                <div class="input-group">
                    <label>Name</label>
                    <input type="text" name="Name" required >
                </div>
                <div class="input-group">
                    <label>Salary</label>
                    <input type="number" name="Salary">
                </div>

                <div class="input-group">
                    <label>Position</label>
                    <input type="text" name="Position" required>
                </div>
                <div class="input-group">
                    <label>Div/Sec/Unit</label>
                    <input type="text" name="Div/Sec/Unit">
                </div>

                <div class="input-group">
                    <label>Departure Date</label>
                    <input type="date" name="Departure_Date" required>
                </div>
                <div class="input-group">
                    <label>Official Station</label>
                    <input type="text" name="Official_Station" required>
                </div>

                <div class="input-group">
                    <label>Destination</label>
                    <input type="text" name="Destination" required>
                </div>
                <div class="input-group">
                    <label>Arrival Date</label>
                    <input type="date" name="Arrival_Date" required>
                </div>

                <div class="input-group full-width" id="purpose-container">
                    <label>Purpose of travel</label>
                    <input type="text" name="Purpose[]" required>
                    <div class="button-group">
                        <button type="button" onclick="addPurpose()">+ Add Purpose</button>
                    </div>
                </div>

                <div class="input-group">
                    <label>Per Diems/Expenses Allowed</label>
                    <input type="text" name="Per_Diems">
                </div>

                <div class="input-group" id="assistant-container">
                    <label>Assistants/Laborers Allowed</label>
                    <input type="text" name="Assistants[]">
                    <div class="button-group">
                        <button type="button" onclick="addAssistant()">+ Add Assistant</button>
                    </div>
                </div>

                <div class="input-group full-width">
                    <label>Appropriation to which travel should be charged</label>
                    <input type="text" name="Appropriation">
                </div>
                
                <div class="input-group full-width">
                    <label>Remarks or Special Instructions</label>
                    <input type="text" name="Remarks">
                </div>
                <!--Select Officer-->
                <div class="input-group full-width">
                    <label>Division Chief</label>
                    <select name="Officer" id="Officer" required>
                        <option value="">Choose Division Chief</option>
                        <option value="101">Antonio C. Marasigan</option>
                        <option value="102">Arlen E. Dayao</option>
                        <option value="103">Cherry</option>
                        <option value="104">Apple</option>
                    </select>
                </div>
                
                <!--esignature -->
               <div class="input-group full-width">
                    <label>Applicant E-Signature (Upload Image)</label>
                    <input type="file" name="e_signature" id="e_signature" accept="image/*">
                </div>
                <!--preview -->
                <div class="full-width" style="display: flex; justify-content: center; margin-top: 10px;">
                    <input type="submit" name="TO_form" value="Preview" formaction="process_TO.php" formtarget="modal_iframe" onclick="toggleCard()">
                </div>
                <div id="modalOverlay" class="modal-overlay" style="display: none;">
                    <div style="width: 500px; height: 650px; border: 1px solid #ccc; margin: auto; background: white; position: relative; display: flex; flex-direction: column;">
                        <button type="button" onclick="toggleCard()" style="position: absolute; top: -25px; right: 0;">Close</button>
                        <iframe name="modal_iframe" style="flex-grow: 1; width: 100%; border: none;"></iframe>
                        <div style="padding: 15px; background: #f1f1f1; border-top: 1px solid #ccc; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <strong>Submit to:</strong>
                                <span id="displayOfficerName">None selected</span>
                            </div>
                            <input type="submit" name="process_final_TO" value="Submit" formaction="process_final_TO.php" formtarget="_parent">
                        </div>
                    </div>
                </div>
                </div>
        </form>
    </div>

</body>
</html>
<script src="function.js"></script>