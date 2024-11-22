function confirmRoleAddition(button, userId) {
    console.log('User ID:', userId);
    window.userIdToAddRole = userId;
    new bootstrap.Modal(document.getElementById('addRoleModal')).show();
}

function addRoleToUser() {
    const selectedRole = document.getElementById('roleSelection').value;
    console.log('User ID to Add Role:', window.userIdToAddRole);
    console.log('Selected Role:', selectedRole);

    if (!window.userIdToAddRole || !selectedRole) {
        alert('Silahkan pilih role.');
        return;
    }

    fetch('/admin/DataUsers/addRole', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ userId: window.userIdToAddRole, role: selectedRole })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Role berhasil ditambahkan.');
                window.location.reload();
            } else {
                console.error('Server response:', data);
                alert('Failed to add role: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan saat menambahkan role.');
        })
        .finally(() => {
            $('#addRoleModal').modal('hide');
        });
}

function confirmUserDeletion(button, userId) {
    console.log('User ID:', userId);
    window.userIdToDelete = userId;
    new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
}

function deleteUser() {
    if (!window.userIdToDelete) {
        alert('User ID tidak tersedia.');
        return;
    }

    fetch('/admin/DataUsers/deleteUser', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ userId: window.userIdToDelete })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User berhasil dihapus.');
                window.location.reload();
            } else {
                console.error('Server response:', data);
                alert('Failed to delete user: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Terjadi kesalahan saat menghapus user.');
        })
        .finally(() => {
            $('#deleteUserModal').modal('hide');
        });
}