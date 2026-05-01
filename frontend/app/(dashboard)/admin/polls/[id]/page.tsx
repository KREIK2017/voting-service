'use client';

import { useEffect, useState, use } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import api from '@/lib/api';
import { Poll, Option } from '@/lib/types';
import { ArrowLeft, Plus, Edit, Trash2, GripVertical } from 'lucide-react';

export default function AdminPollDetailsPage({ params }: { params: Promise<{ id: string }> }) {
  const resolvedParams = use(params);
  const router = useRouter();
  const [poll, setPoll] = useState<Poll | null>(null);
  const [loading, setLoading] = useState(true);
  const [newOption, setNewOption] = useState('');

  const fetchPoll = async () => {
    try {
      const response = await api.get(`/polls/${resolvedParams.id}`);
      setPoll(response.data.data);
    } catch (error) {
      console.error('Failed to fetch poll', error);
      router.push('/admin/polls');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchPoll();
  }, [resolvedParams.id]);

  const handleAddOption = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!newOption.trim()) return;

    try {
      await api.post(`/polls/${resolvedParams.id}/options`, {
        text: newOption,
        order: (poll?.options?.length || 0) + 1
      });
      setNewOption('');
      fetchPoll();
    } catch (error) {
      alert('Failed to add option');
    }
  };

  const handleDeleteOption = async (optionId: number) => {
    if (confirm('Delete this option?')) {
      try {
        await api.delete(`/options/${optionId}`);
        fetchPoll();
      } catch (error) {
        alert('Failed to delete option');
      }
    }
  };

  if (loading) return <div className="animate-pulse space-y-4"><div className="h-8 bg-gray-200 w-1/2 rounded"></div><div className="h-64 bg-gray-100 rounded-xl"></div></div>;
  if (!poll) return null;

  return (
    <div className="max-w-4xl mx-auto space-y-8">
      <div className="flex items-center justify-between">
        <div className="flex items-center space-x-4">
          <Link href="/admin/polls" className="p-2 hover:bg-gray-100 rounded-full transition-colors">
            <ArrowLeft className="w-6 h-6 text-gray-600" />
          </Link>
          <h1 className="text-3xl font-bold text-gray-900">{poll.title}</h1>
        </div>
        <div className="flex space-x-3">
          <Link
            href={`/admin/polls/${poll.id}/edit`}
            className="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
          >
            Edit Poll
          </Link>
          <Link
            href={`/admin/polls/${poll.id}/votes`}
            className="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700"
          >
            View Stats
          </Link>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div className="md:col-span-2 space-y-6">
          <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 className="text-xl font-bold text-gray-900 mb-6">Poll Options</h2>
            
            <div className="space-y-3 mb-8">
              {poll.options?.map((option) => (
                <div key={option.id} className="flex items-center p-4 bg-gray-50 border border-gray-200 rounded-xl group">
                  <GripVertical className="w-5 h-5 text-gray-400 mr-3 cursor-move" />
                  <span className="flex-1 text-gray-900">{option.text}</span>
                  <div className="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button className="p-1 text-gray-400 hover:text-indigo-600">
                      <Edit className="w-4 h-4" />
                    </button>
                    <button onClick={() => handleDeleteOption(option.id)} className="p-1 text-gray-400 hover:text-red-600">
                      <Trash2 className="w-4 h-4" />
                    </button>
                  </div>
                </div>
              ))}
              {poll.options?.length === 0 && (
                <p className="text-center text-gray-500 py-8 italic">No options added yet.</p>
              )}
            </div>

            <form onSubmit={handleAddOption} className="flex space-x-3">
              <input
                type="text"
                value={newOption}
                onChange={(e) => setNewOption(e.target.value)}
                placeholder="Add a new option..."
                className="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
              />
              <button
                type="submit"
                className="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors inline-flex items-center"
              >
                <Plus className="w-5 h-5 mr-1" />
                Add
              </button>
            </form>
          </div>
        </div>

        <div className="space-y-6">
          <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 className="text-lg font-bold text-gray-900 mb-4">Poll Settings</h2>
            <div className="space-y-4">
              <div>
                <span className="text-xs font-semibold text-gray-500 uppercase">Status</span>
                <p className="mt-1">
                  <span className={`px-2 py-0.5 rounded-full text-xs font-bold ${poll.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
                    {poll.is_active ? 'ACTIVE' : 'INACTIVE'}
                  </span>
                </p>
              </div>
              <div>
                <span className="text-xs font-semibold text-gray-500 uppercase">Type</span>
                <p className="text-sm text-gray-900 mt-1">
                  {poll.allow_multiple ? 'Multiple Choice' : 'Single Choice'}
                </p>
              </div>
              <div>
                <span className="text-xs font-semibold text-gray-500 uppercase">Ends</span>
                <p className="text-sm text-gray-900 mt-1">
                  {poll.ends_at ? new Date(poll.ends_at).toLocaleString() : 'Open-ended'}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
