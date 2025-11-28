<?php
// Reusable Import Modal component
// Place this file under /components and include from backend pages with: include __DIR__ . '/../components/import_modal.php';
?>

<!-- Import Modal Component -->
<div id="importDrawerOverlay" class="overlay hidden" aria-hidden="true"></div>
<div id="importDrawer" class="modal hidden" role="dialog" aria-label="Upload Data File" aria-hidden="true">
    <div class="modal">
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <p class="modal-title">Upload Data File</p>
                <button id="importDrawerClose" class="icon-btn" aria-label="Close import drawer">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- File Upload Progress -->
            <div class="file-card">
                <div class="file-icon"><span class="material-symbols-outlined">description</span></div>
                <div class="file-info">
                    <div class="file-header">
                        <p class="file-name">No file selected</p>
                        <p class="file-progress">0%</p>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width:0%"></div>
                    </div>
                    <!-- Validation message area (client-side will update) -->
                    <div id="fileError" class="file-error" role="alert" aria-live="polite"
                        style="display:none;margin-top:.5rem;color:var(--color-red);"></div>
                    <div class="file-hint" style="margin-top:.25rem;font-size:.85rem;color:var(--color-muted);">Allowed:
                        .csv, .xls, .xlsx — Max: 10 MB</div>
                </div>
                <div class="file-action">
                    <button class="icon-btn" id="fileChooseBtn" title="Choose file">
                        <span class="material-symbols-outlined">attach_file</span>
                    </button>
                </div>
            </div>

            <input id="drawerFileInput" type="file" style="display:none"
                accept=".csv,.xls,.xlsx,text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />

            <!-- Review Period -->
            <div class="section">
                <h2 class="section-title">Select Review Period</h2>
                <!-- New Review Period UI -->
                <div class="selected-period">
                    <div>
                        <p class="selected-label">Selected Period</p>
                        <div class="selected-info">
                            <p class="selected-title">Q4 2023 Review</p>
                            <p class="selected-date">Oct 1 - Dec 31, 2023</p>
                        </div>
                    </div>
                    <div class="selected-actions">
                        <!-- Clear button -->
                        <button class="icon-btn primary" id="selectedPeriodClear" aria-label="Clear selected period">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                        <!-- Add new period button -->
                        <button class="icon-btn success" id="addPeriodBtn" aria-label="Add new review period">
                            <span class="material-symbols-outlined">add_circle</span>
                        </button>
                    </div>
                </div>

                <!-- End New Insert Period Date -->

                <div class="period-list">
                    <div class="search-box">
                        <span class="material-symbols-outlined">search</span>
                        <input id="periodSearchInput" type="search" placeholder="Search periods..." />
                    </div>

                    <div class="form-group">
                        <!-- <label>Period List</label> -->
                        <ul id="periodList" role="listbox" tabindex="0" aria-label="Available review periods">
                            <?php
                            $periods = json_decode(getDataAssoc('review_periods',''), true);

                                if (empty($periods)) {
                                    echo "<li class='period-item' role='option' aria-selected='false'>
                                            <span>Review Period Doesn't Exist</span>
                                        </li>";
                                } else {
                                    foreach ($periods as $key => $period) {
                                        echo "<li class='period-item' role='option' tabindex='-1' aria-selected='false' data-id='" . $period['id'] . "'>
                                                <span>" . $period['period_name'] . "</span>
                                                <span class='period-date'>" . $period['start_date'] . " - " . $period['end_date'] . "</span>
                                                <span class='period-desc'>" . $period['description'] . "</span>
                                            </li>";
                                    }
                                }
                                ?>
                        </ul>

                        <!-- Hidden input to store selected period for form submission -->
                        <input type="hidden" name="reviewPeriod" id="reviewPeriodInput" value="" />
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button class="btn cancel" id="drawerCancelBtn">Cancel</button>
                <button class="btn primary" id="drawerUploadBtn">Upload</button>
            </div>

        </div>
    </div>

    <!-- Modal Add New Insert Review Period -->
    <div id="addPeriodModal" class="modal hidden" role="dialog" aria-label="Add Review Period" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title">Add New Review Period</p>
                <button class="icon-btn" id="addPeriodClose" aria-label="Close add period modal">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="newPeriodForm" action="backend/add_period.php" method="post">
                <div class="form-group">
                    <label for="period_name">Period Name</label>
                    <input type="text" id="period_name" name="period_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" id="description" name="description" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn cancel" id="addPeriodCancel">Cancel</button>
                    <button type="submit" class="btn primary" id="drawerUploadBtn"><i
                            class="fas fa-circle-plus"></i>Save</button>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- End Modal Add New Insert Review Period -->

<script>
document.querySelectorAll('#periodList .period-item').forEach(item => {
    item.addEventListener('click', () => {
        // Clear previous selection
        document.querySelectorAll('#periodList .period-item').forEach(i => {
            i.classList.remove('active');
            i.setAttribute('aria-selected', 'false');
        });

        // Mark clicked item as active
        item.classList.add('active');
        item.setAttribute('aria-selected', 'true');

        // Update hidden input
        document.getElementById('reviewPeriodInput').value = item.dataset.id;

        // Update "Selected Period" UI if you have one
        document.querySelector('.selected-title').textContent = item.querySelector('span')
            .textContent;
        document.querySelector('.selected-date').textContent = item.querySelector('.period-date')
            .textContent;
    });
});
</script>


<script>
document.getElementById('addPeriodBtn').addEventListener('click', () => {
    document.getElementById('addPeriodModal').classList.remove('hidden');
    document.getElementById('addPeriodModal').setAttribute('aria-hidden', 'false');
});

document.getElementById('addPeriodClose').addEventListener('click', () => {
    document.getElementById('addPeriodModal').classList.add('hidden');
    document.getElementById('addPeriodModal').setAttribute('aria-hidden', 'true');
});

document.getElementById('addPeriodCancel').addEventListener('click', () => {
    document.getElementById('addPeriodModal').classList.add('hidden');
    document.getElementById('addPeriodModal').setAttribute('aria-hidden', 'true');
});
</script>



<script>
const addPeriodModal = document.getElementById('addPeriodModal');
const firstFocusable = addPeriodModal.querySelector('input, button, select, textarea');
const focusableEls = addPeriodModal.querySelectorAll(
    'input, button, select, textarea, [tabindex]:not([tabindex="-1"])');
const focusable = Array.prototype.slice.call(focusableEls);



function trapFocus(e) {
    const focusedIndex = focusable.indexOf(document.activeElement);
    if (e.key === 'Tab') {
        e.preventDefault();
        if (e.shiftKey) {
            // move backwards
            const prevIndex = (focusedIndex - 1 + focusable.length) % focusable.length;
            focusable[prevIndex].focus();
        } else {
            // move forwards
            const nextIndex = (focusedIndex + 1) % focusable.length;
            focusable[nextIndex].focus();
        }
    }
}

// Open modal
document.getElementById('addPeriodBtn').addEventListener('click', () => {
    addPeriodModal.classList.remove('hidden');
    addPeriodModal.setAttribute('aria-hidden', 'false');
    firstFocusable.focus();
    document.addEventListener('keydown', trapFocus);

    sidebar.classList.add('disabled'); // disable sidebar
    if (pagination) pagination.classList.add('disabled'); // disable pagination
});

// Close modal
function closeAddPeriodModal() {
    addPeriodModal.classList.add('hidden');
    addPeriodModal.setAttribute('aria-hidden', 'true');
    document.removeEventListener('keydown', trapFocus);

    sidebar.classList.remove('disabled'); // re-enable sidebar
    if (pagination) pagination.classList.remove('disabled'); // re-enable pagination

}

document.getElementById('addPeriodClose').addEventListener('click', closeAddPeriodModal);
document.getElementById('addPeriodCancel').addEventListener('click', closeAddPeriodModal);
</script>