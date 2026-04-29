<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<section class="section profile">
<div class="row">
    <div class="col-xl-4">

        <div class="card">
            <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                <img src="<?= base_url()?>NiceAdmin/assets/img/<?=session()->get('foto')?>" alt="Profile" class="rounded-circle" style="max-width: 120px; object-fit: cover;">
                <h2><?= session()->get('username'); ?></h2>
                <h3><?= session()->get('nim'); ?></h3>
                <div class="social-links mt-2">
                    <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8">

        <div class="card">
            <div class="card-body pt-3">
                <!-- Bordered Tabs -->
                <ul class="nav nav-tabs nav-tabs-bordered">

                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-settings">Settings</button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                    </li>

                </ul>
                <div class="tab-content pt-2">

                    <div class="tab-pane fade show active profile-overview" id="profile-overview">
                        <h5 class="card-title">Profile Details</h5>

                        <div class="row">
                            <div class="col-lg-3 col-md-4 label ">Full Name</div>
                            <div class="col-lg-9 col-md-8"><?= session()->get('username'); ?>
                                <span class="badge bg-danger"><?= session()->get('role'); ?></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-4 label">NIM</div>
                            <div class="col-lg-9 col-md-8"><?= session()->get('nim'); ?></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-4 label">Email</div>
                            <div class="col-lg-9 col-md-8">
                                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?= session()->get('email'); ?>" target="_blank">
                                    <?= session()->get('email'); ?>
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-4 label">Phone</div>
                            <div class="col-lg-9 col-md-8">
                                <a href="https://wa.me/<?= session()->get('phone'); ?>" target="_blank">
                                    <?= session()->get('phone'); ?>
                                </a>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-lg-3 col-md-4 label">Login Time</div>
                            <div class="col-lg-9 col-md-8"><?= session()->get('login_time'); ?></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-3 col-md-4 label">Status Login</div>
                            <div class="col-lg-9 col-md-8">
                                <?php if (session()->get('isLoggedIn')) : ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i> Sudah Login
                                    </span>
                                <?php else : ?>
                                <span class="badge bg-secondary">Offline</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                        <!-- Profile Edit Form -->
                        <form>
                        <div class="row mb-3">
                            <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                            <div class="col-md-8 col-lg-9">
                                <img src="<?= base_url()?>NiceAdmin/assets/img/<?=session()->get('foto')?>" alt="Profile" class="rounded-circle" style="max-width: 120px; object-fit: cover;">
                                <div class="pt-2">
                                    <a href="#" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></a>
                                    <a href="#" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="fullName" type="text" class="form-control" id="fullName" value="<?= session()->get('username'); ?>">
                            </div>
                            </div>

                        <div class="row mb-3">
                            <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                            <div class="col-md-8 col-lg-9">
                                <textarea name="about" class="form-control" id="about" style="height: 100px">Sunt est soluta temporibus accusantium neque nam maiores cumque temporibus. Tempora libero non est unde veniam est qui dolor. Ut sunt iure rerum quae quisquam autem eveniet perspiciatis odit. Fuga sequi sed ea saepe at unde.</textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nim" class="col-md-4 col-lg-3 col-form-label">NIM</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="nim" type="text" class="form-control" id="nim" value="<?= session()->get('nim'); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="email" type="text" class="form-control" id="email" value="<?= session()->get('email'); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="phone" type="text" class="form-control" id="phone" value="<?= session()->get('phone'); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Twitter Profile</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="twitter" type="text" class="form-control" id="Twitter" value="https://twitter.com/#">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook Profile</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="facebook" type="text" class="form-control" id="Facebook" value="https://facebook.com/#">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram Profile</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="instagram" type="text" class="form-control" id="Instagram" value="https://instagram.com/#">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin Profile</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="linkedin" type="text" class="form-control" id="Linkedin" value="https://linkedin.com/#">
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                        </form><!-- End Profile Edit Form -->

                    </div>

                    <div class="tab-pane fade pt-3" id="profile-settings">

                    <!-- Settings Form -->
                        <form>

                        <div class="row mb-3">
                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email Notifications</label>
                            <div class="col-md-8 col-lg-9">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="changesMade" checked>
                                <label class="form-check-label" for="changesMade">
                                    Changes made to your account
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="newProducts" checked>
                                <label class="form-check-label" for="newProducts">
                                    Information on new products and services
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="proOffers">
                                <label class="form-check-label" for="proOffers">
                                    Marketing and promo offers
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="securityNotify" checked disabled>
                                <label class="form-check-label" for="securityNotify">
                                    Security alerts
                                </label>
                            </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                        </form><!-- End settings Form -->

                    </div>

                    <div class="tab-pane fade pt-3" id="profile-change-password">
                    <!-- Change Password Form -->
                        <form>

                        <div class="row mb-3">
                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="password" type="password" class="form-control" id="currentPassword">
                            </div>
                        </div>

                        <div class="row mb-3">
                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="newpassword" type="password" class="form-control" id="newPassword">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                            <div class="col-md-8 col-lg-9">
                                <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                        </form><!-- End Change Password Form -->

                    </div>
                </div>
            </div><!-- End Bordered Tabs -->
        </div>
    </div>
</div>
</section>
<?= $this->endSection() ?>