export function formatApiError(err) {
  const d = err.response?.data;
  if (!d) return err.message || 'Something went wrong.';
  if (typeof d.message === 'string') return d.message;
  if (d.message && typeof d.message === 'object') {
    return Object.values(d.message).flat().join(' ');
  }
  if (d.errors && typeof d.errors === 'object') {
    return Object.values(d.errors)
      .flat()
      .filter(Boolean)
      .join(' ');
  }
  return 'Request failed.';
}
