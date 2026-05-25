<x-layouts.app title="Admin Dashboard">
    <section class="admin-edupulse-dashboard">
        <header class="admin-dashboard-topbar">
            <div>
                <h1>Dashboard Overview</h1>
                <p>Welcome back, {{ auth()->user()->name }}</p>
            </div>
            <div>
                <label class="admin-global-search">
                    <span class="material-symbols-outlined">search</span>
                    <input type="search" placeholder="Global Search...">
                </label>
                <button type="button" aria-label="Notifications">
                    <span class="material-symbols-outlined">notifications</span>
                    <i></i>
                </button>
                <div class="admin-profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            </div>
        </header>

        <section class="admin-stat-grid">
            <article class="admin-tonal-card admin-total-card primary">
                <div>
                    <span>Total Students</span>
                    <strong>{{ number_format($totalStudents) }}</strong>
                    <small><span class="material-symbols-outlined">trending_up</span> {{ number_format($overallAverage, 1) }}% overall average</small>
                </div>
                <span class="material-symbols-outlined">school</span>
            </article>
            <article class="admin-tonal-card admin-total-card secondary">
                <div>
                    <span>Active Teachers</span>
                    <strong>{{ number_format($totalTeachers) }}</strong>
                    <small><span class="material-symbols-outlined">check_circle</span> Assigned faculty accounts</small>
                </div>
                <span class="material-symbols-outlined">supervisor_account</span>
            </article>
            <article class="admin-tonal-card admin-total-card tertiary">
                <div>
                    <span>Total Classes</span>
                    <strong>{{ number_format($totalClasses) }}</strong>
                    <small><span class="material-symbols-outlined">room</span> Active academic groups</small>
                </div>
                <span class="material-symbols-outlined">meeting_room</span>
            </article>
            <article class="admin-tonal-card admin-total-card danger">
                <div>
                    <span>Total Users</span>
                    <strong>{{ number_format($totalUsers) }}</strong>
                    <small><span class="material-symbols-outlined">schedule</span> Students, teachers, admins</small>
                </div>
                <span class="material-symbols-outlined">manage_accounts</span>
            </article>
        </section>

        <section class="admin-tonal-card admin-user-card">
            <div class="admin-card-header">
                <div>
                    <h2>User Management</h2>
                    <p>Manage accounts for students, teachers, and staff members.</p>
                </div>
                <div>
                    <label>
                        <span class="material-symbols-outlined">filter_list</span>
                        <select>
                            <option>All Users</option>
                            <option>Students</option>
                            <option>Teachers</option>
                            <option>Admins</option>
                        </select>
                    </label>
                    <a href="{{ route('admin.students.index') }}">
                        <span class="material-symbols-outlined">download</span>
                        Export CSV
                    </a>
                </div>
            </div>

            <div class="admin-user-table-wrap">
                <table class="admin-user-table">
                    <thead>
                        <tr>
                            <th>User Name</th>
                            <th>Role</th>
                            <th>Last Active</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $user)
                            <tr>
                                <td>
                                    <div class="admin-user-cell">
                                        <span>{{ $user['initials'] ?: 'U' }}</span>
                                        <div>
                                            <strong>{{ $user['name'] }}</strong>
                                            <small>{{ $user['email'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user['role'] }}</td>
                                <td>{{ $user['lastActive'] }}</td>
                                <td>
                                    <span class="admin-status-pill {{ $user['status'] === 'Inactive' ? 'inactive' : '' }}">{{ $user['status'] }}</span>
                                </td>
                                <td>
                                    <div>
                                        <button type="button" aria-label="Edit {{ $user['name'] }}">
                                            <span class="material-symbols-outlined">edit</span>
                                        </button>
                                        <button type="button" aria-label="Delete {{ $user['name'] }}" data-delete-user="{{ $user['name'] }}">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="admin-empty-row">No users found yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <footer class="admin-table-footer">
                <p>Showing {{ number_format($recentUsers->count()) }} of {{ number_format($totalUsers) }} users</p>
                <div>
                    <button type="button" disabled><span class="material-symbols-outlined">chevron_left</span></button>
                    <span>Page 1</span>
                    <button type="button"><span class="material-symbols-outlined">chevron_right</span></button>
                </div>
            </footer>
        </section>

        <section class="admin-secondary-grid">
            <article class="admin-tonal-card admin-activity-card">
                <div class="admin-mini-header">
                    <h2>Recent System Activity</h2>
                    <a href="{{ route('admin.scores') }}">View All</a>
                </div>
                <div class="admin-activity-list">
                    @forelse($classAverages->take(3) as $row)
                        <div>
                            <span class="material-symbols-outlined {{ $loop->iteration === 2 ? 'secondary' : ($loop->iteration === 3 ? 'danger' : '') }}">
                                {{ $loop->iteration === 1 ? 'person_add' : ($loop->iteration === 2 ? 'upload' : 'warning') }}
                            </span>
                            <div>
                                <p><strong>{{ $row['teacher'] }}</strong> updated {{ $row['label'] }} performance data.</p>
                                <small>{{ number_format($row['average'], 1) }}% class average</small>
                            </div>
                        </div>
                    @empty
                        <p class="admin-empty-row">No system activity yet.</p>
                    @endforelse
                </div>
            </article>

            <article class="admin-tonal-card admin-events-card">
                <h2>Upcoming Events</h2>
                <div>
                    <div class="active">
                        <time><span>{{ now()->format('M') }}</span><strong>{{ now()->addDays(3)->format('d') }}</strong></time>
                        <div>
                            <strong>Faculty Meeting</strong>
                            <small>09:00 AM - 11:30 AM</small>
                        </div>
                    </div>
                    <div>
                        <time><span>{{ now()->format('M') }}</span><strong>{{ now()->addDays(6)->format('d') }}</strong></time>
                        <div>
                            <strong>Mid-term Registration</strong>
                            <small>All Day Event</small>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.classes.index') }}">Add Event</a>
            </article>
        </section>
    </section>

    <div class="admin-delete-modal hidden" id="deleteModal">
        <div id="modalContent">
            <span class="material-symbols-outlined">delete_forever</span>
            <h2>Delete User?</h2>
            <p>Are you sure you want to delete <strong id="userNameToDelete"></strong>? This action cannot be undone.</p>
            <div>
                <button type="button" data-close-delete>Cancel</button>
                <button type="button" data-confirm-delete>Delete</button>
            </div>
        </div>
    </div>

    <script>
        const deleteModal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('modalContent');
        const userNameToDelete = document.getElementById('userNameToDelete');

        document.querySelectorAll('[data-delete-user]').forEach((button) => {
            button.addEventListener('click', () => {
                userNameToDelete.textContent = button.dataset.deleteUser;
                deleteModal.classList.remove('hidden');
                requestAnimationFrame(() => modalContent.classList.add('is-visible'));
            });
        });

        document.querySelectorAll('[data-close-delete], [data-confirm-delete]').forEach((button) => {
            button.addEventListener('click', () => {
                modalContent.classList.remove('is-visible');
                setTimeout(() => deleteModal.classList.add('hidden'), 180);
            });
        });
    </script>
</x-layouts.app>
