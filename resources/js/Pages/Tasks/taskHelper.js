const statusOptions = [
  { title: 'Pending', value: 'pending' },
  { title: 'In Progress', value: 'in_progress' },
  { title: 'Completed', value: 'completed' },
];

const priorityOptions = [
  { title: 'Low', value: 'low' },
  { title: 'Medium', value: 'medium' },
  { title: 'High', value: 'high' },
];

const perPageOptions = [
  { title: '5', value: 5 },
  { title: '10', value: 10 },
  { title: '25', value: 25 },
  { title: '50', value: 50 },
  { title: '100', value: 100 },
];

const sortByOptions = [
  { title: 'Title', value: 'title' },
  { title: 'Priority', value: 'priority' },
  { title: 'Status', value: 'status' },
  { title: 'Created At', value: 'created_at' },
];

const sortDirections = [
  { title: 'Asc', value: 'desc' },
  { title: 'Desc', value: 'asc' },
];

export { statusOptions, priorityOptions, perPageOptions, sortByOptions, sortDirections };
