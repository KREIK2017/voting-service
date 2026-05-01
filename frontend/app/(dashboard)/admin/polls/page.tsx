'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';
import api from '@/lib/api';
import { Poll } from '@/lib/types';
import { Edit, Trash2, Eye, Plus, BarChart2 } from 'lucide-react';

export default function AdminPollsPage() {
  const [polls, setPolls] = useState<Poll[]>([]);
  const [loading, setLoading] = useState(true);

  const fetchPolls = async () => {
    try {
      const response = await api.get('/polls');
      setPolls(response.data.data);
    } catch (error) {
      console.error('Failed to fetch polls', error);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchPolls();
  }, []);

  const handleDelete = async (id: number) => {
    if (confirm('Are you sure you want to delete this poll? All votes and options will be lost.')) {
      try {
        await api.delete(`/polls/${id}`);
        setPolls(polls.filter(p => p.id !== id));
      } catch (error) {
        alert('Failed to delete poll');
      }
    }
  };

  return (
    <div className="space-y-6">
      <div className="flex justify-between items-center">
        <h1 className="text-3xl font-bold text-gray-900">Manage Polls</h1>
        <Link
          href="/admin/polls/create"
          className="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700"
        >
          <Plus className="w-5 h-5 mr-1" />
          New Poll
        </Link>
      </div>

      <div className="bg-white shadow-sm border border-gray-200 rounded-xl overflow-hidden">
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Votes</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ends At</th>
              <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {loading ? (
              [1, 2, 3].map(i => (
                <tr key={i} className="animate-pulse">
                  <td colSpan={5} className="px-6 py-4 bg-gray-50 h-12"></td>
                </tr>
              ))
            ) : (
              polls.map((poll) => (
                <tr key={poll.id} className="hover:bg-gray-50 transition-colors">
                  <td className="px-6 py-4">
                    <div className="text-sm font-medium text-gray-900">{poll.title}</div>
                    <div className="text-sm text-gray-500 truncate max-w-xs">{poll.description}</div>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap">
                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                      poll.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                    }`}>
                      {poll.is_active ? 'Active' : 'Inactive'}
                    </span>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {poll.votes_count || 0}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {poll.ends_at ? new Date(poll.ends_at).toLocaleDateString() : 'Never'}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                    <Link href={`/admin/polls/${poll.id}`} className="text-blue-600 hover:text-blue-900 inline-block">
                      <Eye className="w-5 h-5" />
                    </Link>
                    <Link href={`/admin/polls/${poll.id}/edit`} className="text-indigo-600 hover:text-indigo-900 inline-block">
                      <Edit className="w-5 h-5" />
                    </Link>
                    <Link href={`/admin/polls/${poll.id}/votes`} className="text-green-600 hover:text-green-900 inline-block">
                      <BarChart2 className="w-5 h-5" />
                    </Link>
                    <button onClick={() => handleDelete(poll.id)} className="text-red-600 hover:text-red-900 inline-block">
                      <Trash2 className="w-5 h-5" />
                    </button>
                  </td>
                </tr>
              ))
            )}
            {!loading && polls.length === 0 && (
              <tr>
                <td colSpan={5} className="px-6 py-12 text-center text-gray-500">
                  No polls created yet. Click "New Poll" to get started.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}
