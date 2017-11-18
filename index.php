<?php
require_once "ShoppingCart.php";

$member_id = 2; // you can your integerate authentication module here to get logged in member

$shoppingCart = new ShoppingCart();
if (! empty($_GET["action"])) {
    switch ($_GET["action"]) {
        case "add":
            if (! empty($_POST["quantity"])) {
                
                $productResult = $shoppingCart->getProductByCode($_GET["code"]);
                
                $cartResult = $shoppingCart->getCartItemByProduct($productResult[0]["id"], $member_id);
                
                if (! empty($cartResult)) {
                    // Update cart item quantity in database
                    $newQuantity = $cartResult[0]["quantity"] + $_POST["quantity"];
                    $shoppingCart->updateCartQuantity($newQuantity, $cartResult[0]["id"]);
                } else {
                    // Add to cart table
                    $shoppingCart->addToCart($productResult[0]["id"], $_POST["quantity"], $member_id);
                }
            }
            break;
        case "remove":
            // Delete single entry from the cart
            $shoppingCart->deleteCartItem($_GET["id"]);
            break;
        case "empty":
            // Empty cart
            $shoppingCart->emptyCart($member_id);
            break;
    }
}
?>
<HTML>
<HEAD>
<TITLE>How to Build a Persistent Shopping Cart in PHP</TITLE>
<link href="style.css" type="text/css" rel="stylesheet" />
</HEAD>
<BODY>
    <div id="shopping-cart">
        <div class="txt-heading">
            <div class="txt-heading-label">Shopping Cart</div> <a id="btnEmpty" href="index.php?action=empty"><img src="empty-cart.png" alt="empty-cart" title="Empty Cart" /></a>
        </div>
<?php
$cartItem = $shoppingCart->getMemberCartItem($member_id);
if (! empty($cartItem)) {
    $item_total = 0;
    ?>	
<table cellpadding="10" cellspacing="1">
            <tbody>
                <tr>
                    <th style="text-align: left;"><strong>Name</strong></th>
                    <th style="text-align: left;"><strong>Code</strong></th>
                    <th style="text-align: right;"><strong>Quantity</strong></th>
                    <th style="text-align: right;"><strong>Price</strong></th>
                    <th style="text-align: center;"><strong>Action</strong></th>
                </tr>	
<?php
    foreach ($cartItem as $item) {
        ?>
				<tr>
                    <td
                        style="text-align: left; border-bottom: #F0F0F0 1px solid;"><strong><?php echo $item["name"]; ?></strong></td>
                    <td
                        style="text-align: left; border-bottom: #F0F0F0 1px solid;"><?php echo $item["code"]; ?></td>
                    <td
                        style="text-align: right; border-bottom: #F0F0F0 1px solid;"><?php echo $item["quantity"]; ?></td>
                    <td
                        style="text-align: right; border-bottom: #F0F0F0 1px solid;"><?php echo "$".$item["price"]; ?></td>
                    <td
                        style="text-align: center; border-bottom: #F0F0F0 1px solid;"><a
                        href="index.php?action=remove&id=<?php echo $item["cart_id"]; ?>"
                        class="btnRemoveAction"><img src="icon-delete.png" alt="icon-delete" title="Remove Item" /></a></td>
                </tr>
				<?php
        $item_total += ($item["price"] * $item["quantity"]);
    }
    ?>

<tr>
                    <td colspan="3" align=right><strong>Total:</strong></td>
                    <td align=right><?php echo "$".$item_total; ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>		
  <?php
}
?>
</div>
<?php require_once "product-list.php"; ?>
    
</BODY>
</HTML>