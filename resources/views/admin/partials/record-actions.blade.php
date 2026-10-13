<div class="admin-record-actions" aria-label="{{ ucfirst($type) }} actions">
  <a href="{{ route('admin.show-' . $type, $record) }}" class="admin-record-action" aria-label="View {{ $record->name ?? $record->prayer_topic ?? ucfirst($type) . ' ' . $record->id }}"><i class="fa-regular fa-eye" aria-hidden="true"></i> View</a>
  <a href="{{ route('admin.edit-' . $type, $record) }}" class="admin-record-action" aria-label="Edit {{ $record->name ?? $record->prayer_topic ?? ucfirst($type) . ' ' . $record->id }}"><i class="fa-regular fa-pen-to-square" aria-hidden="true"></i> Edit</a>
  <a href="{{ route('admin.delete-' . $type, $record) }}" class="admin-record-action admin-record-action-danger" aria-label="Delete {{ $record->name ?? $record->prayer_topic ?? ucfirst($type) . ' ' . $record->id }}"><i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete</a>
</div>
