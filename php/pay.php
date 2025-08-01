<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_SESSION['patient_id']; // Assuming user_id is stored in session
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];
    $card_number = $_POST['card_number'];
    $card_expiry_month = $_POST['card_expiry_month'];
    $card_expiry_year = $_POST['card_expiry_year'];
    $card_cvv = $_POST['card_cvv'];

    // Validate input
    if (empty($amount) || empty($payment_method) || empty($card_number) || empty($card_expiry_month) || empty($card_expiry_year) || empty($card_cvv)) {
        die("All fields are required.");
    }

    // Extract the last four digits of the card number
    $card_last_four = substr($card_number, -4);

    // Prepare and execute the query
    $query = "INSERT INTO payments (patient_id, amount, payment_method, card_last_four, card_expiry_month, card_expiry_year, payment_status) 
              VALUES (?, ?, ?, ?, ?, ?, 'success')";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("idssii", $patient_id, $amount, $payment_method, $card_last_four, $card_expiry_month, $card_expiry_year);

    if ($stmt->execute()) {
        echo "Payment successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Payment Gateway</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            color: #005cbf;
        }
        form {
            max-width: 400px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        form input, form select, form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        form button {
            background-color: #005cbf;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #004494;
        }
        .message {
            text-align: center;
            font-weight: bold;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
    <h1>Enhanced Payment Gateway</h1>

    <?php if (!empty($message)): ?>
        <p class="message <?php echo strpos($message, 'successful') !== false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="pay.php">
    <label for="amount">Amount:</label>
    <input type="number" id="amount" name="amount" required>

    <label for="payment_method">Payment Method:</label>
    <select id="payment_method" name="payment_method" required>
        <option value="credit_card">Credit Card</option>
        <option value="debit_card">Debit Card</option>
    </select>

    <label for="card_number">Card Number:</label>
    <input type="text" id="card_number" name="card_number" maxlength="16" required>

    <label for="card_expiry_month">Expiry Month:</label>
    <select id="card_expiry_month" name="card_expiry_month" required>
        <option value="">Select Month</option>
        <?php for ($i = 1; $i <= 12; $i++): ?>
            <option value="<?php echo $i; ?>"><?php echo sprintf('%02d', $i); ?></option>
        <?php endfor; ?>
    </select>

    <label for="card_expiry_year">Expiry Year:</label>
    <select id="card_expiry_year" name="card_expiry_year" required>
        <option value="">Select Year</option>
        <?php for ($year = date('Y'); $year <= date('Y') + 10; $year++): ?>
            <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
        <?php endfor; ?>
    </select>

    <label for="card_cvv">CVV:</label>
    <input type="text" id="card_cvv" name="card_cvv" maxlength="3" required>

    <button type="submit">Pay Now</button>
</form>

    <script>
        // Mask card number input
        const cardNumberInput = document.getElementById('card_number');
        cardNumberInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digit characters
            value = value.match(/.{1,4}/g)?.join(' ') || value; // Add spaces every 4 digits
            e.target.value = value;
        });

        // Validate CVV
        const cvvInput = document.getElementById('card_cvv');
        cvvInput.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0, 3); // Allow only 3 digits
        });

        // Form validation
        const form = document.getElementById('payment-form');
        form.addEventListener('submit', (e) => {
            const amount = document.getElementById('amount').value;
            const cardNumber = document.getElementById('card_number').value.replace(/\s+/g, ''); // Remove spaces
            const expiry = document.getElementById('card_expiry').value;
            const cvv = document.getElementById('card_cvv').value;

            if (amount <= 0) {
                alert('Please enter a valid amount.');
                e.preventDefault();
                return;
            }

            if (cardNumber.length !== 16 || isNaN(cardNumber)) {
                alert('Please enter a valid 16-digit card number.');
                e.preventDefault();
                return;
            }

            if (!expiry) {
                alert('Please select a valid expiry date.');
                e.preventDefault();
                return;
            }

            if (cvv.length !== 3 || isNaN(cvv)) {
                alert('Please enter a valid 3-digit CVV.');
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>
