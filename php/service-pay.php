<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch and validate user inputs
    $first_name = $conn->real_escape_string(trim($_POST['first_name']));
    $last_name = $conn->real_escape_string(trim($_POST['last_name']));
    $amount = floatval($_POST['amount']);
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    $card_number = preg_replace('/\D/', '', $_POST['card_number']);
    $card_last_four = substr($card_number, -4);
    $card_expiry_month = intval($_POST['card_expiry_month']);
    $card_expiry_year = intval($_POST['card_expiry_year']);
    $card_cvv = preg_replace('/\D/', '', $_POST['card_cvv']);

    // Basic validation
    if (
        empty($first_name) || empty($last_name) || empty($amount) || empty($payment_method) ||
        empty($card_number) || empty($card_expiry_month) ||
        empty($card_expiry_year) || empty($card_cvv)
    ) {
        $_SESSION['error_message'] = "All fields are required and must be valid.";
        header("Location: pay.php");
        exit();
    }

    // Fetch patient_id from the database
    $query = "SELECT id FROM patients WHERE first_name = ? AND last_name = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $first_name, $last_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error_message'] = "No patient found with the entered name.";
        header("Location: pay.php");
        exit();
    }

    $patient = $result->fetch_assoc();
    $patient_id = $patient['id'];

    // Insert payment data
    $query = "INSERT INTO payments 
        (patient_id, amount, payment_method, card_last_four, card_expiry_month, card_expiry_year, payment_status) 
        VALUES (?, ?, ?, ?, ?, ?, 'success')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('idssii', $patient_id, $amount, $payment_method, $card_last_four, $card_expiry_month, $card_expiry_year);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Payment processed successfully.";
        header("Location: payment-history.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Payment processing failed. Please try again.";
        header("Location: pay.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Bill Payment</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Online Bill Payment</h1>

    <?php if (!empty($_SESSION['success_message'])): ?>
        <p class="success"><?php echo htmlspecialchars($_SESSION['success_message']); ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error_message'])): ?>
        <p class="error"><?php echo htmlspecialchars($_SESSION['error_message']); ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <form method="POST">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required>

        <label for="amount">Amount (LKR):</label>
        <input type="number" id="amount" name="amount" step="0.01" required>

        <label for="payment_method">Payment Method:</label>
        <select id="payment_method" name="payment_method" required>
            <option value="Credit Card">Credit Card</option>
            <option value="Debit Card">Debit Card</option>
            <option value="Online Banking">Online Banking</option>
        </select>

        <label for="card_number">Card Number:</label>
        <input type="text" id="card_number" name="card_number" maxlength="16" required>

        <label for="card_expiry_month">Expiry Month:</label>
        <input type="number" id="card_expiry_month" name="card_expiry_month" min="1" max="12" required>

        <label for="card_expiry_year">Expiry Year:</label>
        <input type="number" id="card_expiry_year" name="card_expiry_year" min="<?php echo date('Y'); ?>" required>

        <label for="card_cvv">CVV:</label>
        <input type="text" id="card_cvv" name="card_cvv" maxlength="3" required>

        <button type="submit">Make Payment</button>
    </form>
</body>
</html>
