<?php
include 'config/database.php';

// Get event ID from URL
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch event details
$event = null;
if ($event_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM historical_events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $event = $result->fetch_assoc();
    }
    $stmt->close();
}

// If no event found, redirect to timeline
if (!$event) {
    header('Location: timeline.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['title']); ?> | Indian History Timeline</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        
        .event-container {
            max-width: 900px;
            margin: 100px auto 50px;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .event-year {
            background: #1a237e;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        .event-title {
            color: #1a237e;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .btn-back {
            background: #1a237e;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        
        .btn-back:hover {
            background: #283593;
            color: white;
        }
        
        /* New styles for image */
        .event-image {
            max-height: 400px;
            width: auto;
            border: 5px solid #1a237e;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            margin-bottom: 20px;
        }
        
        .image-container {
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <!-- Simple Navigation -->
    <nav style="background: #1a237e; color: white; padding: 15px; position: fixed; top: 0; width: 100%; z-index: 1000;">
        <div class="container">
            <a href="index.php" style="color: white; text-decoration: none; font-weight: bold;">
                <i class="fas fa-history"></i> HistoryTimeline
            </a>
        </div>
    </nav>
    
    <div class="container">
        <div class="event-container">
            <span class="event-year"><?php echo $event['year']; ?> CE</span>
            <h1 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h1>
            
            <!-- ===== IMAGE DISPLAY SECTION - ADD THIS ===== -->
            <?php if(!empty($event['image_path'])): ?>
            <div class="image-container">
                <img src="<?php echo $event['image_path']; ?>" 
                     alt="<?php echo htmlspecialchars($event['title']); ?>" 
                     class="event-image">
                <?php if(file_exists($event['image_path'])): ?>
                    <p class="text-muted small">Image: <?php echo basename($event['image_path']); ?></p>
                <?php endif; ?>
            </div>
            <?php else: ?>
            <div class="image-container">
                <div style="background: #f0f0f0; padding: 40px; border-radius: 10px;">
                    <i class="fas fa-image fa-3x text-muted"></i>
                    <p class="text-muted mt-2">No image available for this event</p>
                </div>
            </div>
            <?php endif; ?>
            <!-- ===== END OF IMAGE DISPLAY SECTION ===== -->
            
            <p><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
            <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($event['date'])); ?></p>
            
            <div style="margin: 20px 0;">
                <h4>Description:</h4>
                <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
            </div>
            
            <?php if(!empty($event['significance'])): ?>
            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;">
                <h4><i class="fas fa-star"></i> Historical Significance</h4>
                <p><?php echo nl2br(htmlspecialchars($event['significance'])); ?></p>
            </div>
            <?php endif; ?>
            
            <?php if($event['category']): ?>
            <p><strong>Category:</strong> <span style="background: #ffab00; padding: 5px 15px; border-radius: 15px;"><?php echo $event['category']; ?></span></p>
            <?php endif; ?>
            
            <div style="margin-top: 30px;">
                <a href="timeline.php" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Back to Timeline
                </a>
                <a href="user/login.php" style="margin-left: 10px; color: #1a237e; text-decoration: none;">
                    <i class="fas fa-user me-2"></i>Login to Save Event
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>