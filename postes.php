<?php
require_once 'Database.php';
include 'template.php';
$db = Database::getInstance();
$connection = $db->getConnection();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header('Location: index.php');
  exit;
}

$query = $connection->prepare('SELECT * FROM posts WHERE id = :id');
$query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$query->execute();

$post = $query->fetch(PDO::FETCH_ASSOC);

if (!$post) {
  header('Location: index.php');
  exit;
}

$query = $connection->prepare('SELECT * FROM comments WHERE postId = :postId ORDER BY createdAt DESC LIMIT 0, 2');
$query->bindParam(':postId', $_GET['id'], PDO::PARAM_INT);
$query->execute();
$comments = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
  <h1><?php echo $post['title']; ?></h1>
  <p class="text-muted">Publié le <?php echo $post['createdAt']; ?></p>
  <p><?php echo $post['body']; ?></p>

  <h2 class="mt-5">Commentaires</h2>
  <div id="comments">
    <?php if (count($comments) === 0) : ?>
      <p class="mb-5">Aucun commentaire</p>
    <?php else : ?>
      <?php foreach ($comments as $comment) : ?>
        <div class="comment card mb-3">
          <div class="card-header"><?php echo $comment['email']; ?></div>
          <div class="card-body">
            <p class="text-muted">Publié le <?php echo $comment['createdAt']; ?></p>
            <p><?php echo $comment['body']; ?></p>
            <button class="btn btn-sm btn-danger delete-comment" data-comment-id="<?php echo $comment['id']; ?>">Supprimer</button>
          </div>
          <p id="delete-comment"></p>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <button id="load-more-comments" class="btn btn-primary mb-5">Voir plus de commentaires</button>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    var offset = 2;
    var limit = 2;
    var postId = <?php echo $_GET['id']; ?>;

    function loadComments() {
      $.ajax({
        url: 'load-comments.php',
        method: 'GET',
        data: {
          postId: postId,
          offset: offset,
          limit: limit
        },
        success: function(response) {
          var commentsContainer = $('#comments');
          commentsContainer.append(response);

          var newComments = commentsContainer.find('.comment').slice(offset);
          newComments.each(function() {
            var comment = $(this);
            var commentId = comment.data('comment-id');
            comment.append('<button class="delete-comment btn btn-sm btn-danger" data-comment-id="' + commentId + '">Supprimer</button>');
            comment.find('.delete-comment').click(function() {
              var commentId = $(this).data('comment-id');
              DeleteComments(commentId);
            });
          });

          offset += limit;

          if (response.trim() == '') {
            $('#load-more-comments').hide();
          }
        }
      });
    }

    function DeleteComments(commentId) {
      $.ajax({
        url: 'delete-comments.php',
        method: 'POST',
        data: {
          id: commentId
        },
        success: function(response) {
          if (response.trim() == 'success') {
            $('#comment-' + commentId).remove();
            $('#delete-comment').text('Commentaire supprimé avec succès.');
          } else {
            $('#delete-comment').text('Vous navez pas le droit de supprimer ce commentaire.');
          }
        }
      });
    }

    $('#load-more-comments').click(loadComments);

    $(document).on('click', '.delete-comment', function() {
      var commentId = $(this).data('comment-id');
      DeleteComments(commentId);
    });
  });
</script>