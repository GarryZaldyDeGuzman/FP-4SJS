<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$select_likes->execute([$user_id]);
$total_likes = $select_likes->rowCount();

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <!-- quick select section starts  -->

    <section class="quick-select">

        <h1 class="heading">quick options</h1>

        <div class="box-container">

            <?php
            if ($user_id != '') {
            ?>
                <div class="box">
                    <h3 class="title">likes and comments</h3>
                    <p>total likes : <span><?= $total_likes; ?></span></p>
                    <a href="likes.php" class="inline-btn">view likes</a>
                    <p>total comments : <span><?= $total_comments; ?></span></p>
                    <a href="comments.php" class="inline-btn">view comments</a>
                    <p>saved strands : <span><?= $total_bookmarked; ?></span></p>
                    <a href="bookmark.php" class="inline-btn">view bookmark</a>
                </div>
            <?php
            } else {
            }
            ?>

            <div class="box">
                <h3 class="title">Subjects</h3>
                <div class="flex">
                    <!-- Update the href and teacher names accordingly -->
                    <a href="search_strand.php?"><i class="fas fa-code"></i><span>teachername1</span></a>
                    <a href="#"><i class="fas fa-chart-simple"></i><span>teachername2</span></a>
                    <a href="#"><i class="fas fa-pen"></i><span>teachername3</span></a>
                    <a href="#"><i class="fas fa-chart-line"></i><span>teachername4</span></a>
                </div>
            </div>

        </div>

    </section>

    <!-- quick select section ends -->

    <!-- strands section starts  -->

    <section class="strands">

        <h1 class="heading">latest strands</h1>

        <div class="box-container">

            <?php
            $select_strands = $conn->prepare("SELECT * FROM `strands` WHERE status = ? ORDER BY date DESC LIMIT 6");
            $select_strands->execute(['active']);
            if ($select_strands->rowCount() > 0) {
                while ($fetch_strand = $select_strands->fetch(PDO::FETCH_ASSOC)) {
                    $strand_id = $fetch_strand['id'];

                    // You may need to modify this part based on your database structure for strands and tutors
                    $select_teacher = $conn->prepare("SELECT * FROM `teachers` WHERE id = ?");
                    $select_teacher->execute([$fetch_strand['teacher_id']]);
                    $fetch_teacher = $select_teacher->fetch(PDO::FETCH_ASSOC);
            ?>
                    <div class="box">
                        <div class="teacher">
                            <img src="uploaded_files/<?= $fetch_teacher['image']; ?>" alt="">
                            <div>
                                <h3><?= $fetch_teacher['name']; ?></h3>
                                <span><?= $fetch_strand['date']; ?></span>
                            </div>
                        </div>
                        <!-- Update the image and title accordingly -->
                        <img src="uploaded_files/<?= $fetch_strand['image']; ?>" class="thumb" alt="">
                        <h3 class="title"><?= $fetch_strand['title']; ?></h3>
                        <a href="strand.php?get_id=<?= $strand_id; ?>" class="inline-btn">view strand</a>
                    </div>
            <?php
                }
            } else {
                echo '<p class="empty">no strands added yet!</p>';
            }
            ?>

        </div>

        <div class="more-btn">
            <a href="strands.php" class="inline-option-btn">view more</a>
        </div>

    </section>

    <!-- strands section ends -->

    <!-- footer section starts  -->
    <?php include 'components/footer.php'; ?>
    <!-- footer section ends -->

    <!-- custom js file link  -->
    <script src="js/script.js"></script>

</body>
            
</html>
