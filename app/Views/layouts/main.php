<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Clinic Portal') ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f6f9; color: #333; }
        
        header { background-color: #17a2b8; color: white; padding: 20px 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        
        /* 2. Cấu trúc hộp menu xếp dọc và căn giữa tuyệt đối */
        .header-container { max-width: 1100px; margin: 0 auto; padding: 0 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; text-align: center; box-sizing: border-box; }
        
        /* Chỉnh lại tiêu đề menu */
        .header-container h2 { margin: 0; font-size: 24px; font-weight: bold; }
        
        /* Chỉnh lại khoảng cách các link menu */
        .header-container nav { display: flex; justify-content: center; align-items: center; gap: 15px; flex-wrap: wrap; }
        .header-container nav a { color: white; text-decoration: none; }
        .header-container nav a:hover { text-decoration: underline; }

        .container { max-width: 1100px; margin: 30px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); box-sizing: border-box; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: white; }
        th, td { padding: 12px 15px; border: 1px solid #dee2e6; text-align: left; }
        th { background-color: #f8f9fa; font-weight: 600; color: #495057; }
        tr:hover { background-color: #f1f3f5; }
        .btn { display: inline-block; padding: 8px 14px; color: white; background-color: #007bff; border: none; border-radius: 4px; text-decoration: none; cursor: pointer; font-size: 14px; }
        .btn:hover { background-color: #0056b3; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #bd2130; }
        .btn-success { background-color: #28a745; }
        .btn-success:hover { background-color: #218838; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .form-control { width: 100%; padding: 8px 12px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box; }
        .text-danger { color: #dc3545; font-size: 13px; margin-top: 4px; display: block; }
        .alert { padding: 12px 15px; border-radius: 4px; margin-bottom: 20px; font-weight: 500; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .pagination { margin-top: 20px; display: flex; gap: 5px; list-style: none; padding: 0; }
        .pagination a { padding: 6px 12px; border: 1px solid #dee2e6; color: #007bff; text-decoration: none; border-radius: 4px; }
        .pagination .active { padding: 6px 12px; background-color: #007bff; color: white; border-radius: 4px; border: 1px solid #007bff; }
    </style>
</head>
<body>

<header>
    <div class="header-container">
        <?php partial('nav'); ?>
    </div>
</header>

<div class="container">
    <?php partial('flash'); ?>
    <?= $content ?>
</div>

</body>
</html>