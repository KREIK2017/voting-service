'use client';

import { useEffect, useState, use } from 'react';
import Link from 'next/link';
import api from '@/lib/api';
import { Poll } from '@/lib/types';
import VoteForm from '@/components/VoteForm';
import { ArrowLeft, Clock } from 'lucide-react';

export default function PollVotingPage({ params }: { params: Promise<{ id: string }> }) {
  const resolvedParams = use(params);
  const [poll, setPoll] = useState<Poll | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchPoll = async () => {
      try {
        const response = await api.get(`/polls/${resolvedParams.id}`);
        setPoll(response.data.data);
      } catch (error) {
        console.error('Failed to fetch poll', error);
      } finally {
        setLoading(false);
      }
    };

    fetchPoll();
  }, [resolvedParams.id]);

  if (loading) return <div className="animate-pulse space-y-4"><div className="h-12 bg-gray-200 w-3/4 rounded"></div><div className="h-96 bg-gray-100 rounded-2xl"></div></div>;
  if (!poll) return <div className="text-center py-20">Poll not found.</div>;

  return (
    <div className="max-w-2xl mx-auto space-y-8">
      <Link href="/polls" className="inline-flex items-center text-indigo-600 hover:text-indigo-500 font-medium">
        <ArrowLeft className="w-4 h-4 mr-2" />
        Back to all polls
      </Link>

      <div className="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900 mb-4">{poll.title}</h1>
          <p className="text-gray-600 leading-relaxed">{poll.description}</p>
        </div>

        <div className="flex items-center text-sm text-gray-500 mb-8 p-3 bg-gray-50 rounded-lg">
          <Clock className="w-4 h-4 mr-2" />
          <span>Ends: {poll.ends_at ? new Date(poll.ends_at).toLocaleString() : 'Open-ended'}</span>
        </div>

        <VoteForm poll={poll} />
      </div>

      <div className="text-center">
        <Link href={`/polls/${poll.id}/results`} className="text-sm text-gray-500 hover:text-indigo-600">
          View current results without voting
        </Link>
      </div>
    </div>
  );
}
