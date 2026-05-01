'use client';

import { useEffect, useState, use } from 'react';
import Link from 'next/link';
import api from '@/lib/api';
import { Poll } from '@/lib/types';
import ResultsBar from '@/components/ResultsBar';
import { ArrowLeft, Users, RefreshCcw } from 'lucide-react';

export default function PollResultsPage({ params }: { params: Promise<{ id: string }> }) {
  const resolvedParams = use(params);
  const [poll, setPoll] = useState<Poll | null>(null);
  const [loading, setLoading] = useState(true);

  const fetchResults = async () => {
    setLoading(true);
    try {
      const response = await api.get(`/polls/${resolvedParams.id}/results`);
      // When inside response()->json(), Laravel resources are NOT wrapped in 'data'
      const pollData = response.data.poll;
      
      if (pollData) {
        pollData.options = response.data.results || [];
        pollData.votes_count = response.data.total_votes || 0;
        setPoll(pollData);
      } else {
        throw new Error('No poll data found');
      }
    } catch (error) {
      console.error('Failed to fetch results', error);
      setPoll(null);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchResults();
  }, [resolvedParams.id]);

  if (loading && !poll) {
    return (
      <div className="flex flex-col items-center justify-center py-20 space-y-4">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
        <p className="text-gray-500 font-medium">Loading results...</p>
      </div>
    );
  }

  if (!poll) {
    return (
      <div className="max-w-2xl mx-auto text-center py-20">
        <p className="text-xl text-red-600 font-bold mb-4">Poll results not available.</p>
        <Link href="/polls" className="text-indigo-600 hover:underline">Return to all polls</Link>
      </div>
    );
  }

  return (
    <div className="max-w-2xl mx-auto space-y-8">
      <Link href="/polls" className="inline-flex items-center text-indigo-600 hover:text-indigo-500 font-medium">
        <ArrowLeft className="w-4 h-4 mr-2" />
        Back to all polls
      </Link>

      <div className="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <div className="flex justify-between items-start mb-8">
          <div>
            <h1 className="text-3xl font-bold text-gray-900 mb-2">{poll.title}</h1>
            <p className="text-gray-600">Current Results</p>
          </div>
          <button 
            onClick={fetchResults}
            className="p-2 text-gray-400 hover:text-indigo-600 rounded-full hover:bg-gray-50 transition-colors"
            title="Refresh results"
          >
            <RefreshCcw className={`w-5 h-5 ${loading ? 'animate-spin' : ''}`} />
          </button>
        </div>

        <div className="space-y-8">
          {poll.options?.map((option) => (
            <ResultsBar
              key={option.id}
              text={option.text}
              count={option.votes_count || 0}
              percentage={option.percentage || 0}
            />
          ))}
        </div>

        <div className="mt-12 pt-8 border-t border-gray-100 flex items-center justify-between">
          <div className="flex items-center text-gray-500">
            <Users className="w-5 h-5 mr-2" />
            <span className="font-medium">{poll.votes_count} total votes cast</span>
          </div>
          <Link
            href={`/polls/${poll.id}`}
            className="text-indigo-600 font-semibold hover:text-indigo-700"
          >
            Back to Poll &rarr;
          </Link>
        </div>
      </div>
    </div>
  );
}
