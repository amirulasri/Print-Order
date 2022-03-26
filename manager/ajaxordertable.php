<?php
include("../conn.php");
$orderid = $_REQUEST['order'];
$increment = 1;
try {
    $statementgetitems = $conn->prepare("SELECT * FROM items WHERE custid = ?");
    $statementgetitems->execute([$orderid]);
    while ($row = $statementgetitems->fetch(PDO::FETCH_NUM)) {
?>
        <tr>
            <td><?php echo $increment++ ?></td>
            <td><?php echo $row[1]; ?></td>
            <td>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edititemmodal" id="papertypebtn<?php echo $row[0]; ?>" onclick="getitemdatatomodal(this.id)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
                        <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z" />
                    </svg>
                    Edit
                </button>
                <br>RM 0.00
            </td>
            <td><?php
                $statustext = "";
                $badgecolor = "";
                if (strtolower($row[5]) == 'printing') {
                    $statustext = "Printing";
                    $badgecolor = "primary";
                } else if (strtolower($row[5]) == 'complete') {
                    $statustext = "Complete";
                    $badgecolor = "success";
                } else if (strtolower($row[5]) == 'failed') {
                    $statustext = "Print failed";
                    $badgecolor = "danger";
                } else if (strtolower($row[5]) == 'cancelled') {
                    $statustext = "Print cancelled";
                    $badgecolor = "danger";
                }
                ?>
                <span class="badge rounded-pill bg-<?php echo $badgecolor ?>"><?php echo $statustext ?></span>
            </td>
        </tr>
<?php
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>