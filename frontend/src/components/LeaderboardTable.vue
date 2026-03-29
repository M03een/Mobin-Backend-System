<script setup>
defineProps({
  rows: { type: Array, default: () => [] },
  loading: { type: Boolean, default: false },
  emptyMessage: { type: String, default: 'No entries yet.' },
});
</script>

<template>
  <div class="table-wrap">
    <table v-if="!loading && rows.length">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th class="num">Points</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(row, i) in rows" :key="row.id">
          <td>{{ i + 1 }}</td>
          <td>{{ row.username }}</td>
          <td class="num">{{ row.points }}</td>
        </tr>
      </tbody>
    </table>
    <p v-else-if="loading" class="muted">Loading…</p>
    <p v-else class="muted">{{ emptyMessage }}</p>
  </div>
</template>

<style scoped>
.table-wrap {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.95rem;
}

th,
td {
  text-align: left;
  padding: 0.65rem 0.75rem;
  border-bottom: 1px solid var(--border);
}

th {
  color: var(--muted);
  font-weight: 600;
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.num {
  text-align: right;
  font-variant-numeric: tabular-nums;
}

.muted {
  color: var(--muted);
  margin: 0;
}
</style>
