'use client';

import { useEffect, useState, use } from 'react';
import { useRouter } from 'next/navigation';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import api from '@/lib/api';
import Link from 'next/link';
import { ArrowLeft } from 'lucide-react';

const pollSchema = z.object({
  title: z.string().min(3, 'Title must be at least 3 characters'),
  description: z.string().optional(),
  starts_at: z.string().optional(),
  ends_at: z.string().optional(),
  is_active: z.boolean(),
  allow_multiple: z.boolean(),
});

type PollFormValues = z.infer<typeof pollSchema>;

export default function EditPollPage({ params }: { params: Promise<{ id: string }> }) {
  const resolvedParams = use(params);
  const router = useRouter();
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);

  const {
    register,
    handleSubmit,
    reset,
    formState: { errors },
  } = useForm<PollFormValues>({
    resolver: zodResolver(pollSchema),
  });

  useEffect(() => {
    const fetchPoll = async () => {
      try {
        const response = await api.get(`/polls/${resolvedParams.id}`);
        const poll = response.data.data;
        reset({
          title: poll.title,
          description: poll.description || '',
          starts_at: poll.starts_at ? poll.starts_at.slice(0, 16) : '',
          ends_at: poll.ends_at ? poll.ends_at.slice(0, 16) : '',
          is_active: poll.is_active,
          allow_multiple: poll.allow_multiple,
        });
      } catch (err) {
        console.error('Failed to fetch poll', err);
        router.push('/admin/polls');
      } finally {
        setLoading(false);
      }
    };

    fetchPoll();
  }, [resolvedParams.id, reset, router]);

  const onSubmit = async (data: PollFormValues) => {
    setSaving(true);
    setError(null);
    try {
      await api.put(`/polls/${resolvedParams.id}`, data);
      router.push(`/admin/polls/${resolvedParams.id}`);
    } catch (err: any) {
      setError(err.response?.data?.message || 'Failed to update poll.');
    } finally {
      setSaving(false);
    }
  };

  if (loading) return <div className="animate-pulse space-y-4"><div className="h-12 bg-gray-200 w-1/2 rounded"></div></div>;

  return (
    <div className="max-w-2xl mx-auto space-y-6">
      <div className="flex items-center space-x-4">
        <Link href={`/admin/polls/${resolvedParams.id}`} className="p-2 hover:bg-gray-100 rounded-full transition-colors">
          <ArrowLeft className="w-6 h-6 text-gray-600" />
        </Link>
        <h1 className="text-3xl font-bold text-gray-900">Edit Poll</h1>
      </div>

      <div className="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        {error && (
          <div className="mb-6 p-4 text-sm text-red-700 bg-red-100 rounded-lg">
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
          <div>
            <label className="block text-sm font-medium text-gray-700">Poll Title</label>
            <input
              {...register('title')}
              className="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
            />
            {errors.title && <p className="mt-1 text-xs text-red-600">{errors.title.message}</p>}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700">Description</label>
            <textarea
              {...register('description')}
              rows={3}
              className="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>

          <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div>
              <label className="block text-sm font-medium text-gray-700">Starts At</label>
              <input
                {...register('starts_at')}
                type="datetime-local"
                className="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
              />
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700">Ends At</label>
              <input
                {...register('ends_at')}
                type="datetime-local"
                className="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
              />
            </div>
          </div>

          <div className="space-y-4">
            <div className="flex items-center">
              <input
                {...register('is_active')}
                type="checkbox"
                id="is_active"
                className="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label htmlFor="is_active" className="ml-2 block text-sm text-gray-900">
                Poll is active
              </label>
            </div>

            <div className="flex items-center">
              <input
                {...register('allow_multiple')}
                type="checkbox"
                id="allow_multiple"
                className="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label htmlFor="allow_multiple" className="ml-2 block text-sm text-gray-900">
                Allow multiple selections
              </label>
            </div>
          </div>

          <div className="pt-4 flex space-x-3">
            <button
              type="submit"
              disabled={saving}
              className="flex-1 flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400"
            >
              {saving ? 'Saving...' : 'Save Changes'}
            </button>
            <Link
              href={`/admin/polls/${resolvedParams.id}`}
              className="flex-1 flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
            >
              Cancel
            </Link>
          </div>
        </form>
      </div>
    </div>
  );
}
