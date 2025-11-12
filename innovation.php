<?php
include("config.php");

// --- Handle Single Innovation Detail ---
$detail = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM innovations WHERE id=$id AND status='approved'");
    if ($result && $result->num_rows > 0) {
        $detail = $result->fetch_assoc();
    }
}

// --- Search/Filter ---
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$categoryFilter = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$dateFilter = isset($_GET['date']) ? $conn->real_escape_string($_GET['date']) : '';

$where = "status='approved'";
if ($search) $where .= " AND title LIKE '%$search%'";
if ($categoryFilter) $where .= " AND category='$categoryFilter'";
if ($dateFilter) $where .= " AND DATE(created_at)='$dateFilter'";

$innovations = $conn->query("SELECT * FROM innovations WHERE $where ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Innovation Hub - Hossana Science & Technology</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ===== Global Styles ===== */
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto:wght@400;500&display=swap');

body {
  font-family: 'Roboto', sans-serif;
  margin:0; padding:0;
  background: linear-gradient(135deg,#0A1931,#142850,#1E2A78);
  color: #fff;
  overflow-x:hidden;
}
a { text-decoration:none; color: inherit; }

/* ===== Header ===== */
header {
  background: rgba(10,25,49,0.95);
  padding:15px 30px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  border-bottom:2px solid #00FFFF;
  position:sticky; top:0; z-index:1000;
  box-shadow:0 0 20px rgba(0,255,255,0.3);
}
header h1 { font-family: 'Orbitron', sans-serif; color:#00FFFF; font-size:28px; letter-spacing:1px; }
header nav a { margin:0 12px; font-weight:500; color:#00FFFF; transition:0.3s; }
header nav a:hover { color:#fff; text-shadow:0 0 10px #00FFFF; }

/* ===== Hero Section ===== */
.hero {
  text-align:center; padding:140px 20px;
  background: url('https://images.unsplash.com/photo-1581090700227-7e8b07f2b5ee?auto=format&fit=crop&w=1600&q=80') no-repeat center center/cover;
  position: relative; overflow:hidden;
}
.hero::after {
  content:"";
  position:absolute; top:0; left:0; width:100%; height:100%;
  background: rgba(10,25,49,0.75);
}
.hero h2 { font-family:'Orbitron', sans-serif; font-size:50px; color:#00FFFF; margin-bottom:20px; text-shadow:0 0 15px #00FFFF; position:relative; z-index:1; }
.hero p { font-size:20px; color:#C0C0C0; max-width:750px; margin:auto; line-height:1.6; position:relative; z-index:1; }
.hero .btn { position:relative; z-index:1; display:inline-block; margin:15px; padding:16px 36px; font-size:18px; color:#00FFFF; border:2px solid #00FFFF; border-radius:8px; transition:0.3s; box-shadow:0 0 15px rgba(0,255,255,0.5); }
.hero .btn:hover { background:#00FFFF; color:#0A1931; transform:scale(1.1); box-shadow:0 0 25px #00FFFF; }

/* ===== Sections ===== */
.section { padding:80px 20px; text-align:center; position:relative; z-index:1; }
.section-title { font-size:36px; color:#00FFFF; margin-bottom:40px; font-family:'Orbitron', sans-serif; text-shadow:0 0 8px #00FFFF; }

/* ===== Cards ===== */
.grid { display:grid; gap:25px; }
.grid-3 { grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); }
.card {
  background: rgba(0,255,255,0.05); border-radius:14px;
  padding:25px; border:1px solid rgba(0,255,255,0.3);
  transition: transform 0.3s, box-shadow 0.3s;
  backdrop-filter: blur(6px);
  position:relative;
}
.card:hover { transform:translateY(-8px) scale(1.06); box-shadow:0 0 35px #00FFFF; }
.card h3 { color:#00FFFF; margin-bottom:10px; font-family:'Orbitron', sans-serif; }
.card p { color:#C0C0C0; margin-bottom:8px; line-height:1.5; }
.card .btn { border-color:#00FFFF; color:#00FFFF; margin-top:8px; display:inline-block; }
.card .btn:hover { background:#00FFFF; color:#0A1931; box-shadow:0 0 25px #00FFFF; }

/* ===== Category Icons ===== */
.category-icon { font-size:20px; margin-right:6px; vertical-align:middle; color:#00FFFF; }

/* ===== Forms ===== */
form input, form select, form button, textarea {
  padding:12px 16px; margin:5px; border-radius:6px; border:1px solid #00FFFF;
  background: rgba(0,255,255,0.05); color:#fff; transition:0.3s;
}
form button {
  background:#00FFFF; color:#0A1931; font-weight:bold; cursor:pointer; transition:0.3s;
}
form button:hover { background:#fff; color:#0A1931; box-shadow:0 0 15px #00FFFF; }

/* ===== Related Innovations ===== */
.related-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:20px; margin-top:40px; }
.related-card { background: rgba(0,255,255,0.05); padding:20px; border-radius:12px; border:1px solid rgba(0,255,255,0.3); transition:0.3s; }
.related-card:hover { transform:translateY(-5px); box-shadow:0 0 25px #00FFFF; }

/* ===== Footer ===== */
footer {
  background:#000; color:#00FFFF; text-align:center; padding:40px 20px; border-top:1px solid #00FFFF;
  box-shadow:0 0 20px rgba(0,255,255,0.3);
}
footer a { color:#00FFFF; margin:0 10px; transition:0.3s; }
footer a:hover { color:#fff; }

/* ===== Responsive ===== */
@media (max-width:768px) { .grid-3, .related-grid { grid-template-columns:1fr; } form input, form select, form button, textarea { width:90%; } }
</style>
</head>
<body>

<header>
  <h1>Innovation Hub - HST</h1>
  <nav>
    <a href="innovation.php">Home</a>
    <a href="#about">About</a>
    <a href="submit_innovation.php">Submit</a>
  </nav>
</header>

<section class="hero">
  <h2>Welcome to the Innovation Hub</h2>
  <p>Empowering innovators, developers, and tech enthusiasts to create real-world impact in Hossana.</p>
  <a href="login.php" class="btn">Submit Your Innovation</a>
</section>
<!-- ===== About Section ===== -->
<section id="about" class="section" style="background: linear-gradient(135deg, rgba(0,255,255,0.05), rgba(0,255,255,0.1)); border-radius:12px; margin:40px auto; padding:60px 20px;">
  <h2 class="section-title">About Innovation Hub</h2>

  <!-- What is Innovation -->
  <div style="max-width:900px; margin:auto; font-size:18px; line-height:1.8; color:#C0C0C0; margin-bottom:50px;">
    <h3 style="color:#FFD700;">What is Innovation?</h3>
    <p>
      Innovation is the art of transforming ideas into impactful solutions. It is the process of developing new technologies, products, or methods that improve life, solve problems, or create new opportunities. Innovation is not just invention, but the practical application of creativity for real-world benefits.
    </p>
  </div>

  <!-- Best Innovators -->
  <div style="max-width:900px; margin:auto; font-size:18px; line-height:1.8; color:#C0C0C0; margin-bottom:50px;">
    <h3 style="color:#FFD700;">World’s Best Innovators</h3>
    <p>
      Our hub celebrates global innovators who changed the world with their ideas:
    </p>
    <ul style="list-style-type:circle; margin-left:20px;">
      <li><b>Elon Musk</b> – Revolutionized electric vehicles with Tesla, space travel with SpaceX, and sustainable energy solutions.</li>
      <li><b>Steve Jobs</b> – Co-founder of Apple, transformed personal computing, smartphones, and digital media.</li>
      <li><b>Marie Curie</b> – Pioneer in radioactivity research, contributing to science and medicine worldwide.</li>
      <li><b>Tim Berners-Lee</b> – Inventor of the World Wide Web, enabling global information sharing and connectivity.</li>
      <li><b>Jeff Bezos</b> – Founder of Amazon, innovating e-commerce, logistics, and cloud computing.</li>
    </ul>
    <p>These innovators inspire us to create technology solutions that have real impact locally and globally.</p>
  </div>

  <!-- History of Innovation Hub -->
  <div style="max-width:900px; margin:auto; font-size:18px; line-height:1.8; color:#C0C0C0; margin-bottom:50px;">
    <h3 style="color:#FFD700;">History of Innovation Hub</h3>
    <p>
      Founded in 2015, the Hossana Science & Technology Innovation Hub started as a small gathering of tech enthusiasts. 
      Over the years, it has grown into a vibrant platform supporting innovators in various fields, hosting workshops, hackathons, and mentorship programs. 
      Today, it serves as a bridge between creative ideas and real-world solutions, driving innovation in Hossana and beyond.
    </p>
  </div>

  <!-- About the Hub -->
  <div style="max-width:900px; margin:auto; font-size:18px; line-height:1.8; color:#C0C0C0; margin-bottom:50px;">
    <h3 style="color:#FFD700;">About the Hub</h3>
    <p>
      Our mission is to nurture technology-driven creativity, providing resources, mentorship, and collaboration opportunities. 
      We focus on domains like <i>Artificial Intelligence</i>, <i>Green Energy</i>, <i>Biotechnology</i>, <i>Software & Apps</i>, and <i>Industrial Technology</i>. 
      Innovators here are guided to create solutions that improve communities, contribute to scientific progress, and inspire future generations.
    </p>
  </div>

  <!-- Visual Cards: Icons for Innovation -->
  <div class="grid grid-3" style="margin-top:40px;">
    <div class="card">
      <i class="fa-solid fa-lightbulb category-icon" style="font-size:36px; color:#FFD700;"></i>
      <h3>Innovation</h3>
      <p>Creating practical solutions that solve real-world challenges.</p>
    </div>
    <div class="card">
      <i class="fa-solid fa-rocket category-icon" style="font-size:36px; color:#FFD700;"></i>
      <h3>Impact</h3>
      <p>Developing technologies that improve communities and industries.</p>
    </div>
    <div class="card">
      <i class="fa-solid fa-users category-icon" style="font-size:36px; color:#FFD700;"></i>
      <h3>Collaboration</h3>
      <p>Connecting innovators, mentors, and tech enthusiasts to share ideas.</p>
    </div>
  </div>
</section>


<?php if($detail): ?>
<section class="section">
  <h2 class="section-title"><?= htmlspecialchars($detail['title']) ?></h2>
  <p><i class="fa-solid fa-user category-icon"></i><b>Author:</b> <?= htmlspecialchars($detail['author'] ?? 'Anonymous') ?></p>
  <p><i class="fa-solid fa-layer-group category-icon"></i><b>Category:</b> <?= htmlspecialchars($detail['category']) ?></p>
  <p><i class="fa-solid fa-calendar-days category-icon"></i><b>Date:</b> <?= htmlspecialchars($detail['created_at']) ?></p>
  <p><b>Estimated Impact:</b> <?= htmlspecialchars($detail['impact'] ?? 'N/A') ?></p>
  <p><b>Technology Used:</b> <?= htmlspecialchars($detail['technology'] ?? 'N/A') ?></p>
  <p><b>Required Resources:</b> <?= htmlspecialchars($detail['resources'] ?? 'N/A') ?></p>
  <p><?= nl2br(htmlspecialchars($detail['description'])) ?></p>
  <?php if(!empty($detail['pdf_path'])): ?>
    <a href="<?= htmlspecialchars($detail['pdf_path']) ?>" class="btn" target="_blank"><i class="fa-solid fa-file-pdf"></i> Download PDF</a>
  <?php endif; ?>
  <p><b>Votes:</b> <span id="vote-count"><?= intval($detail['votes'] ?? 0) ?></span></p>
  <button class="btn" onclick="voteInnovation(<?= $detail['id'] ?>)"><i class="fa-solid fa-thumbs-up"></i> Vote</button>
</section>

<section class="section">
  <h3 class="section-title">Comments</h3>
  <?php
  $comments = $conn->query("SELECT * FROM innovation_comments WHERE innovation_id={$detail['id']} ORDER BY created_at DESC");
  if ($comments && $comments->num_rows > 0) {
      while ($c = $comments->fetch_assoc()) {
          echo "<div class='card'><p><b>" . htmlspecialchars($c['user']) . ":</b> " . nl2br(htmlspecialchars($c['comment'])) . "</p></div>";
      }
  } else { echo "<p>No comments yet.</p>"; }
  ?>
  <form method="post" action="submit_comment.php">
      <input type="hidden" name="innovation_id" value="<?= $detail['id'] ?>">
      <input type="text" name="user" placeholder="Your name" required>
      <textarea name="comment" placeholder="Your comment" required></textarea>
      <button type="submit"><i class="fa-solid fa-paper-plane"></i> Submit Comment</button>
  </form>
</section>

<section class="section">
  <h3 class="section-title">Related Innovations</h3>
  <div class="related-grid">
  <?php
  $related = $conn->query("SELECT * FROM innovations WHERE id!={$detail['id']} AND category='".($detail['category'] ?? '')."' AND status='approved' LIMIT 4");
  while($r=$related->fetch_assoc()){
      echo "<div class='related-card'><h4>".htmlspecialchars($r['title'])."</h4><p>".substr(htmlspecialchars($r['description']),0,120)."...</p>";
      echo "<a href='innovation.php?id=".$r['id']."' class='btn'>View Details</a></div>";
  }
  ?>
  </div>
</section>

<a href="innovation.php" class="btn" style="margin:20px; display:inline-block;">← Back to Innovations</a>

<?php else: ?>
<section class="section">
  <h2 class="section-title">Approved Innovations</h2>
  <form method="get">
    <input type="text" name="search" placeholder="Search by title" value="<?= htmlspecialchars($search) ?>">
    <select name="category">
      <option value="">All Categories</option>
      <option value="AI" <?= $categoryFilter=="AI"?"selected":""?>>Artificial Intelligence</option>
      <option value="Green Energy" <?= $categoryFilter=="Green Energy"?"selected":""?>>Green Energy</option>
      <option value="Biotech" <?= $categoryFilter=="Biotech"?"selected":""?>>Biotechnology</option>
      <option value="Software" <?= $categoryFilter=="Software"?"selected":""?>>Software & Apps</option>
      <option value="Industrial" <?= $categoryFilter=="Industrial"?"selected":""?>>Industrial Tech</option>
    </select>
    <input type="date" name="date" value="<?= htmlspecialchars($dateFilter) ?>">
    <button type="submit">Filter</button>
  </form>

  <div class="grid grid-3">
  <?php if($innovations && $innovations->num_rows>0): ?>
      <?php while($row = $innovations->fetch_assoc()): ?>
          <div class="card">
              <h3><?= htmlspecialchars($row['title']) ?></h3>
              <p><i class="fa-solid fa-user category-icon"></i><b>Author:</b> <?= htmlspecialchars($row['author'] ?? 'Anonymous') ?></p>
              <p><i class="fa-solid fa-layer-group category-icon"></i><b>Category:</b> <?= htmlspecialchars($row['category']) ?></p>
              <p><i class="fa-solid fa-calendar-days category-icon"></i><b>Date:</b> <?= htmlspecialchars($row['created_at']) ?></p>
              <p><?= substr($row['description'],0,150) ?>...</p>
              <?php if(!empty($row['pdf_path'])): ?>
                  <a href="<?= htmlspecialchars($row['pdf_path']) ?>" class="btn" target="_blank"><i class="fa-solid fa-file-pdf"></i> PDF</a>
              <?php endif; ?>
              <a href="innovation.php?id=<?= $row['id'] ?>" class="btn"><i class="fa-solid fa-eye"></i> View Details</a>
          </div>
      <?php endwhile; ?>
  <?php else: ?>
      <p>No approved innovations yet.</p>
  <?php endif; ?>
  </div>
</section>
<?php endif; ?>


<footer>
  <p>© 2025 Hossana Science & Technology</p>
  <p>
    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
    <a href="#"><i class="fa-brands fa-twitter"></i></a>
    <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
  </p>
</footer>

<script>
// --- Vote Button ---
function voteInnovation(id){
  fetch('vote.php?id='+id)
  .then(res=>res.text())
  .then(data=>{
      document.getElementById('vote-count').textContent = data;
      alert('Thanks for voting!');
  });
}
</script>
</body>
</html>
