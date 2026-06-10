<?php
require_once __DIR__ . "/../../core/helpers.php";
require_once __DIR__ . "/../../core/CSRF.php";

$pageTitle = "Users Management";

$searchFilter = $_GET["search"] ?? "";
$roleFilter = $_GET["role"] ?? "";

require_once __DIR__ . "/../partials/header.php";
require_once __DIR__ . "/../partials/navbar.php";
require_once __DIR__ . "/../partials/sidebar.php";
?>

<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">
            <h1>Users Management</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            <?php require_once __DIR__ . "/../partials/alerts.php"; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Search & Filter</h3>
                </div>

                <div class="card-body">
                    <form method="get" action="index.php">
                        <input type="hidden" name="page" value="users">
                        <input type="hidden" name="action" value="index">

                        <div class="row">
                            <div class="col-md-5">
                                <label>Search by name or email</label>
                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       value="<?php echo sanitize($searchFilter); ?>"
                                       placeholder="Enter name or email">
                            </div>

                            <div class="col-md-4">
                                <label>Role</label>
                                <select name="role" class="form-control">
                                    <option value="">All Roles</option>
                                    <option value="admin" <?php if ($roleFilter === "admin") echo "selected"; ?>>Admin</option>
                                    <option value="doctor" <?php if ($roleFilter === "doctor") echo "selected"; ?>>Doctor</option>
                                    <option value="patient" <?php if ($roleFilter === "patient") echo "selected"; ?>>Patient</option>
                                </select>
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-2">
                                    Filter
                                </button>

                                <a href="index.php?page=users&action=index" class="btn btn-secondary">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users List</h3>

                    <div class="card-tools">
                        <a href="index.php?page=users&action=createForm" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add User
                        </a>
                    </div>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($user = $users->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $user["id"]; ?></td>
                                    <td><?php echo sanitize($user["name"]); ?></td>
                                    <td><?php echo sanitize($user["email"]); ?></td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?php echo sanitize($user["role"]); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ((int) $user["is_active"] === 1): ?>
                                            <span class="badge badge-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-warning"
                                           href="index.php?page=users&action=editForm&id=<?php echo $user["id"]; ?>">
                                            Edit
                                        </a>

                                        <form method="post"
                                              action="index.php?page=users&action=delete"
                                              style="display:inline;">

                                            <input type="hidden" name="id" value="<?php echo $user["id"]; ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this user?');">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <nav>
                        <ul class="pagination">

                            <?php
                            $queryParams = $_GET;
                            unset($queryParams["p"]);
                            ?>

                            <?php if ($paginator->hasPrev()): ?>
                                <?php $queryParams["p"] = $paginator->currentPage() - 1; ?>

                                <li class="page-item">
                                    <a class="page-link"
                                       href="index.php?<?php echo http_build_query($queryParams); ?>">
                                        Previous
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $paginator->totalPages(); $i++): ?>
                                <?php $queryParams["p"] = $i; ?>

                                <li class="page-item <?php if ($i === $paginator->currentPage()) echo 'active'; ?>">
                                    <a class="page-link"
                                       href="index.php?<?php echo http_build_query($queryParams); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($paginator->hasNext()): ?>
                                <?php $queryParams["p"] = $paginator->currentPage() + 1; ?>

                                <li class="page-item">
                                    <a class="page-link"
                                       href="index.php?<?php echo http_build_query($queryParams); ?>">
                                        Next
                                    </a>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </nav>
                </div>
            </div>

        </div>
    </section>

</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>