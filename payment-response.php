<?php
session_start();
include "includes/db.php"; // Add this line to include database connection

// Get the pidx from the URL
$pidx = $_GET['pidx'] ?? null;

if ($pidx) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/lookup/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(['pidx' => $pidx]),
        CURLOPT_HTTPHEADER => array(
            'Authorization: key live_secret_key_68791341fdd94846a146f0457ff7b455',
            'Content-Type: application/json',
        ),
    ));
 

    $response = curl_exec($curl);
    curl_close($curl);

    if ($response) {
        $responseArray = json_decode($response, true);
        switch ($responseArray['status']) {
            case 'Completed':
                // ADD THIS SECTION - Save order to database after successful payment
                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];
                    
                    // Get cart items and calculate total
                    $subtotal = 0;
                    $delivery_charge = 100;
                    $cart_items = [];
                    
                    $cart_query = "SELECT c.*, p.title, p.price FROM cart c JOIN photos p ON c.photo_id = p.id WHERE c.user_id = '$user_id'";
                    $cart_result = mysqli_query($conn, $cart_query);
                    
                    while ($row = mysqli_fetch_assoc($cart_result)) {
                        $size_extra = match ($row['print_size']) {
                            '5x7' => 20,
                            'A4' => 50,
                            'A3' => 100,
                            default => 0
                        };
                        $type_extra = match ($row['print_type']) {
                            'Matte' => 20,
                            'Canvas' => 50,
                            default => 0
                        };
                        $final_price = $row['price'] + $size_extra + $type_extra;
                        $item_subtotal = $final_price * $row['quantity'];
                        $subtotal += $item_subtotal;
                        
                        $cart_items[] = [
                            'photo_id' => $row['photo_id'],
                            'title' => $row['title'],
                            'image_path' => $row['image_path'],
                            'quantity' => $row['quantity'],
                            'print_size' => $row['print_size'],
                            'print_type' => $row['print_type'],
                            'subtotal' => $item_subtotal
                        ];
                    }
                    
                    $total_amount = $subtotal + $delivery_charge;
                    
                    // Insert into orders table
                    $order_date = date('Y-m-d H:i:s');
                    $order_query = "INSERT INTO orders (user_id, total_amount, order_date, status, payment_method) 
                                   VALUES ('$user_id', '$total_amount', '$order_date', 'pending', 'Khalti')";
                    
                    if (mysqli_query($conn, $order_query)) {
                        $order_id = mysqli_insert_id($conn);
                        
                        // Insert into order_items table
                        foreach ($cart_items as $item) {
                            // Insert order item
                            $item_query = "INSERT INTO order_items (order_id, photo_id, title, image_path, quantity, print_size, print_type, subtotal) 
                                        VALUES ('$order_id', '{$item['photo_id']}', '{$item['title']}', '{$item['image_path']}', '{$item['quantity']}', '{$item['print_size']}', '{$item['print_type']}', '{$item['subtotal']}')";
                            mysqli_query($conn, $item_query);

                            // Reduce stock for each photo
                            $update_stock_query = "UPDATE photos SET stock = stock - {$item['quantity']} WHERE id = {$item['photo_id']}";
                            mysqli_query($conn, $update_stock_query);
                        }

                            // Insert billing info
                        $billing_name = $_SESSION['billing_name'] ?? 'N/A';
                        $billing_email = $_SESSION['billing_email'] ?? 'N/A';
                        $billing_address = $_SESSION['billing_address'] ?? 'N/A';
                        $billing_phone = $_SESSION['billing_phone'] ?? 'N/A';


                        $billing_query = "INSERT INTO billing_info (order_id, user_id, name, email, address, phone) 
                                        VALUES ('$order_id', '$user_id', '$billing_name', '$billing_email', '$billing_address', '$billing_phone')";
                        mysqli_query($conn, $billing_query);

                        // Clear the cart after successful order
                        $clear_cart_query = "DELETE FROM cart WHERE user_id = '$user_id'";
                        mysqli_query($conn, $clear_cart_query);
                    }
                }
                // END OF ADDED SECTION

                $_SESSION['transaction_msg'] = '<script>
                        Swal.fire({
                            icon: "success",
                            title: "Transaction successful.",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>';


                header("Location: message.php");
                exit();
              
            case 'Expired':
            case 'User canceled':
                //here you can write your logic to update the database
                $_SESSION['transaction_msg'] = '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Transaction failed.",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>';
                header("Location: checkout.php");
                exit();
                
            default:
            //here you can write your logic to update the database
                $_SESSION['transaction_msg'] = '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Transaction failed.",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>';
                header("Location: checkout.php");
                exit();
                
        }
    }
}
?>