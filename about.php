<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ShopSphere | About</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>about us</h3>
   <p><a href="home.php">Home</a> <span> / About</span></p>
</div>

<!-- about section starts  -->

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/about-img.svg" alt="">
      </div>

      <div class="content">
      <h3>Why Choose Us?</h3>

<p>Welcome to ShopSphere where choice meets trust!
At ShopSphere, were passionate about delivering an exceptional shopping experience. Whether you're browsing for daily essentials, the latest gadgets, or trendy fashion, we bring quality products right to your fingertips.</p>

<div class="features">
   <p><strong>🛍️ Quality First:</strong> <span>We prioritize hygienic, flavorful food prepared with care.</span></p>
   <p><strong>⚡ Fast & Reliable Delivery:</strong> <span>Get your orders quickly and securely across the country.</span></p>
   <p><strong>🤝 Customer-Centric Service:</strong> <span>Friendly responsive support that puts you first.</span></p>
   <p><strong>📱 Smart Shopping Platform:</strong> <span>Easy navigation, secure payments, and real-time tracking.</span></p>
</div>

<p>ShopSphere isn’t just an e-commerce site — it’s your trusted destination for a seamless and satisfying shopping journey. Discover the difference with ShopSphere, where every click brings confidence.</p>

<a href="menu.php" class="btn">Our Menu</a>
      </div>

      

   </div>

</section>

<!-- about section ends -->

<!-- steps section starts  -->

<section class="steps">
   <h1 class="title">Simple Steps</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/step-1.png" alt="Choose Order">
         <h3>Choose Your Product</h3>
         <p>Your order is packed with care and delivered to your doorstep quickly and safely.</p>
      </div>

      <div class="box">
         <img src="images/step-2.png" alt="Fast Delivery">
         <h3>Fast Delivery</h3>
         <p>Your order is prepared fresh and delivered to your doorstep quickly and safely.</p>
      </div>

      <div class="box">
         <img src="images/step-3.png" alt="Enjoy products">
         <h3>Enjoy Your Purchase</h3>
         <p>Relax and enjoy your new product — carefully selected, packed, and delivered with care by ShopSphere.</p>
      </div>

   </div>
</section>

<section class="reviews">
   <h1 class="title">Customer Reviews</h1>

   <div class="swiper reviews-slider">
      <div class="swiper-wrapper">

         <div class="swiper-slide slide">
            <img src="images/pic-02.jpg" alt="Customer Review 1">
            <p>ShopSphere never disappoints! The products are genuine, well-packed, and delivered right on time. Highly recommend!</p>
            <div class="stars">
               <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
            </div>
            <h3>Deepak Thakur</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/pic-2.png" alt="Customer Review 2">
            <p>I love the variety on ShopSphere! It’s easy to place orders, the delivery is quick, and the quality of products impresses me every time!</p>
            <div class="stars">
               <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <h3>Mamta Priya</h3>
         </div>

         <div class="swiper-slide slide">
            <img src="images/pic-3.png" alt="Customer Review 3">
            <p>Excellent service! Customer support was friendly, and my special requests were handled perfectly. Truly appreciated!</p>
            <div class="stars">
               <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <h3>Mike Taylor</h3>
         </div>

      </div>
      <div class="swiper-pagination"></div>
   </div>

</section>

<!-- reviews section ends -->



















<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->=






<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".reviews-slider", {
   loop:true,
   grabCursor: true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
      slidesPerView: 1,
      },
      700: {
      slidesPerView: 2,
      },
      1024: {
      slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>