<?php
echo "Hashed Admin Password: " . password_hash('admin123', PASSWORD_DEFAULT) . "<br>";
echo "Hashed Staff Password: " . password_hash('staff123', PASSWORD_DEFAULT) . "<br>";
echo "Hashed Patient Password: " . password_hash('patient123', PASSWORD_DEFAULT) . "<br>";
?>
