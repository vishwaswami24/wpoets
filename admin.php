<?php
declare(strict_types=1);

?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Slider Admin</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    .mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
  </style>
</head>
<body>
<div class="container py-4">
  <h1 class="h3 mb-3">Slider CRUD</h1>

  <div class="row g-3">
    <div class="col-lg-5">
      <div class="card">
        <div class="card-body">
          <h2 class="h5 mb-3">Add / Update</h2>

          <input type="hidden" id="id" value="" />

          <div class="mb-2">
            <label class="form-label">Title</label>
            <input class="form-control" id="title" type="text" />
          </div>

          <div class="mb-2">
            <label class="form-label">Web Image Path (1:1 for column 3)</label>
            <input class="form-control mono" id="web_image_path" type="text" placeholder="files/images/DL-Communication.jpg" />
            <div class="form-text">Keep this path relative so it works in the browser.</div>
          </div>

          <div class="mb-2">
            <label class="form-label">Mobile Background Image Path (optional)</label>
            <input class="form-control mono" id="mobile_bg_image_path" type="text" placeholder="files/images/DL-communication.jpg" />
          </div>

          <div class="mb-3">
            <label class="form-label">Sort Order</label>
            <input class="form-control" id="sort_order" type="number" value="0" />
          </div>

          <div class="d-flex gap-2">
            <button class="btn btn-primary" id="btn-save">Save</button>
            <button class="btn btn-secondary" id="btn-reset" type="button">Reset</button>
          </div>

          <div class="mt-3 small" id="status"></div>
        </div>
      </div>
    </div>

    <div class="col-lg-7">
      <div class="card">
        <div class="card-body">
          <h2 class="h5 mb-3">Existing</h2>
          <div class="table-responsive">
            <table class="table table-sm align-middle" id="items-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Title</th>
                  <th>Web Image</th>
                  <th>Mobile BG</th>
                  <th>Sort</th>
                  <th style="width: 170px;">Actions</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  async function loadItems() {
    const res = await fetch('api/list.php');
    const data = await res.json();
    const items = data.items || [];

    const tbody = document.querySelector('#items-table tbody');
    tbody.innerHTML = '';

    items.forEach((it) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td class="mono">${it.id}</td>
        <td>${escapeHtml(it.title)}</td>
        <td class="mono"><a href="${escapeAttr(it.web_image_path)}" target="_blank">link</a></td>
        <td class="mono">${it.mobile_bg_image_path ? `<a href="${escapeAttr(it.mobile_bg_image_path)}" target="_blank">link</a>` : '-'}</td>
        <td>${it.sort_order}</td>
        <td>
          <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-primary" data-edit="${it.id}">Edit</button>
            <button class="btn btn-sm btn-outline-danger" data-del="${it.id}">Delete</button>
          </div>
        </td>
      `;
      tbody.appendChild(tr);
    });

    tbody.onclick = async (e) => {
      const editBtn = e.target.closest('[data-edit]');
      const delBtn = e.target.closest('[data-del]');

      if (editBtn) {
        const id = editBtn.getAttribute('data-edit');
        const item = items.find(x => String(x.id) === String(id));
        if (!item) return;

        document.getElementById('id').value = item.id;
        document.getElementById('title').value = item.title;
        document.getElementById('web_image_path').value = item.web_image_path;
        document.getElementById('mobile_bg_image_path').value = item.mobile_bg_image_path || '';
        document.getElementById('sort_order').value = item.sort_order;
        document.getElementById('status').textContent = 'Editing item #' + item.id;
      }

      if (delBtn) {
        const id = delBtn.getAttribute('data-del');
        if (!confirm('Delete item #' + id + '?')) return;
        const fd = new FormData();
        fd.append('id', id);
        const r = await fetch('api/delete.php', { method: 'POST', body: fd });
        const j = await r.json();
        document.getElementById('status').textContent = j.ok ? 'Deleted.' : (j.error || 'Error');
        await loadItems();
        resetForm();
      }
    };
  }

  function escapeHtml(str) {
    return String(str).replace(/[&<>"']/g, (c) => ({'&':'&amp;','<':'<','>':'>','"':'"','\'':'&#39;'}[c]));
  }
  function escapeAttr(str) {
    return String(str).replace(/"/g, '"');
  }

  function getForm() {
    return {
      id: document.getElementById('id').value,
      title: document.getElementById('title').value.trim(),
      web_image_path: document.getElementById('web_image_path').value.trim(),
      mobile_bg_image_path: document.getElementById('mobile_bg_image_path').value.trim(),
      sort_order: document.getElementById('sort_order').value
    };
  }

  function setStatus(msg) {
    document.getElementById('status').textContent = msg;
  }

  async function save() {
    const f = getForm();
    if (!f.title) return setStatus('Title is required');
    if (!f.web_image_path) return setStatus('Web image path is required');

    const fd = new FormData();
    fd.append('title', f.title);
    fd.append('web_image_path', f.web_image_path);
    fd.append('mobile_bg_image_path', f.mobile_bg_image_path);
    fd.append('sort_order', f.sort_order);

    const isEdit = f.id && Number(f.id) > 0;
    if (isEdit) {
      fd.append('id', f.id);
      const r = await fetch('api/update.php', { method: 'POST', body: fd });
      const j = await r.json();
      setStatus(j.ok ? 'Updated.' : (j.error || 'Error'));
    } else {
      const r = await fetch('api/create.php', { method: 'POST', body: fd });
      const j = await r.json();
      setStatus(j.ok ? 'Created #' + j.id : (j.error || 'Error'));
      resetForm();
    }

    await loadItems();
  }

  function resetForm() {
    document.getElementById('id').value = '';
    document.getElementById('title').value = '';
    document.getElementById('web_image_path').value = '';
    document.getElementById('mobile_bg_image_path').value = '';
    document.getElementById('sort_order').value = 0;
    setStatus('');
  }

  document.getElementById('btn-save').addEventListener('click', save);
  document.getElementById('btn-reset').addEventListener('click', resetForm);

  loadItems();
</script>
</body>
</html>

