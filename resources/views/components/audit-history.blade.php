@props(['model'])

@can('view-audit-history')
    <div x-data="auditHistoryComponent({ userId: '{{ $model->getKey() }}' })" class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Change History</h3>
            <button x-on:click="load()" class="myds-btn myds-btn--tertiary">Load</button>
        </div>

        <div class="card-body p-0">
            <template x-if="!loaded">
                <div class="p-3 text-muted">Click "Load" to fetch the change history (admin only).</div>
            </template>

            <template x-if="loaded">
                <div>
                    <div x-show="audits.length === 0" class="p-3 text-muted">No changes recorded.</div>

                    <div class="table-responsive" x-show="audits.length > 0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>When</th>
                                    <th>Admin</th>
                                    <th>Action</th>
                                    <th>Changes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="audit in audits" :key="audit.id">
                                    <tr>
                                        <td style="white-space:nowrap" x-text="formatDate(audit.created_at)"></td>
                                        <td x-html="formatUser(audit.user, audit.user_id)"></td>
                                        <td x-text="audit.event"></td>
                                        <td>
                                            <div style="max-width:60ch;overflow:auto">
                                                <ul class="mb-0 pl-3" x-html="formatChanges(audit)"></ul>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 d-flex justify-content-center" x-show="pagination.total > pagination.per_page">
                        <button class="myds-btn myds-btn--tertiary me-2" x-on:click="prevPage()" :disabled="!pagination.prev_page">Prev</button>
                        <span class="align-self-center">Page <span x-text="pagination.current_page"></span> of <span x-text="pagination.last_page"></span></span>
                        <button class="myds-btn myds-btn--tertiary ms-2" x-on:click="nextPage()" :disabled="!pagination.next_page">Next</button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
        function auditHistoryComponent({ userId }) {
            const baseUrl = {!! json_encode(route('users.audits', $model)) !!};
            const csrf = {!! json_encode(csrf_token()) !!};

            return {
                userId,
                loaded: false,
                audits: [],
                pagination: {},
                page: 1,
                perPage: 10,
                async load() {
                    if (this.loaded && this.audits.length) return;
                    await this.fetchPage(1);
                    this.loaded = true;
                },
                async fetchPage(page = 1) {
                    const url = `${baseUrl}?page=${page}&per_page=${this.perPage}`;
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf } });
                    if (!res.ok) {
                        console.error('Failed to load audits', res.status);
                        this.audits = [];
                        return;
                    }
                    const data = await res.json();
                    this.audits = data.data;
                    this.pagination = {
                        total: data.total,
                        per_page: data.per_page,
                        current_page: data.current_page,
                        last_page: data.last_page,
                        prev_page: data.prev_page_url !== null,
                        next_page: data.next_page_url !== null,
                    };
                    this.page = this.pagination.current_page;
                },
                prevPage() { if (this.page > 1) { this.page--; this.fetchPage(this.page); } },
                nextPage() { if (this.page < (this.pagination.last_page || 1)) { this.page++; this.fetchPage(this.page); } },
                formatDate(dt) {
                    try { return new Date(dt).toLocaleString(); } catch (e) { return dt; }
                },
                formatUser(user, userId) {
                    if (!user) return '<span class="text-muted">System / Unknown</span>';
                    return user.name || user.email || `ID ${userId}`;
                },
                formatChanges(audit) {
                    const oldValues = audit.old_values || {};
                    const newValues = audit.new_values || {};
                    const keys = Array.from(new Set([...Object.keys(oldValues), ...Object.keys(newValues)]));
                    if (!keys.length) return '<li class="text-muted">No attribute-level details available.</li>';
                    return keys.map(k => {
                        const o = typeof oldValues[k] === 'object' ? JSON.stringify(oldValues[k]) : (oldValues[k] === null ? '<em>null</em>' : String(oldValues[k]));
                        const n = typeof newValues[k] === 'object' ? JSON.stringify(newValues[k]) : (newValues[k] === null ? '<em>null</em>' : String(newValues[k]));
                        return `<li><strong>${k}</strong>: <span class="text-muted">from</span> ${escapeHtml(o)} <span class="text-muted">to</span> ${escapeHtml(n)}</li>`;
                    }).join('');
                }
            };

            function escapeHtml(unsafe) {
                return String(unsafe)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }
        }
    </script>
@endcan
