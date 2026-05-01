'use client';

import { useEffect, useState, use } from 'react';
import Link from 'next/link';
import api from '@/lib/api';
import { Poll, Vote } from '@/lib/types';
import { ArrowLeft, User, CheckCircle } from 'lucide-react';
import ResultsBar from '@/components/ResultsBar';

export default function AdminPollVotesPage({ params }: { params: Promise<{ id: string }> }) {
  const resolvedParams = use(params);
  const [poll, setPoll] = useState<Poll | null>(null);
  const [votes, setVotes] = useState<Vote[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [pollRes, votesRes] = await Promise.all([
          api.get(`/polls/${resolvedParams.id}/results`),
          api.get(`/polls/${resolvedParams.id}/votes`)
        ]);
        setPoll(pollRes.data.data);
        setVotes(votesRes.data.data);
      } catch (error) {
        console.error('Failed to fetch stats', error);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, [resolvedParams.id]);

  if (loading) return <div className="animate-pulse space-y-4"><div className="h-12 bg-gray-200 w-3/4 rounded"></div><div className="h-64 bg-gray-100 rounded-xl"></div></div>;
  if (!poll) return null;

  return (
    <div className="max-w-5xl mx-auto space-y-8">
      <div className="flex items-center space-x-4">
        <Link href={`/admin/polls/${poll.id}`} className="p-2 hover:bg-gray-100 rounded-full transition-colors">
          <ArrowLeft className="w-6 h-6 text-gray-600" />
        </Link>
        <div>
          <h1 className="text-3xl font-bold text-gray-900">Detailed Statistics</h1>
          <p className="text-gray-600">{poll.title}</p>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div className="lg:col-span-1 space-y-6">
          <div className="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 className="text-xl font-bold text-gray-900 mb-6">Aggregate Results</h2>
            <div className="space-y-6">
              {poll.options?.map((option) => (
                <ResultsBar
                  key={option.id}
                  text={option.text}
                  count={option.votes_count || 0}
                  percentage={option.percentage || 0}
                />
              ))}
            </div>
            <div className="mt-8 pt-6 border-t border-gray-100">
              <div className="flex justify-between text-sm font-medium">
                <span className="text-gray-500">Total Votes Cast:</span>
                <span className="text-indigo-600">{poll.votes_count}</span>
              </div>
            </div>
          </div>
        </div>

        <div className="lg:col-span-2">
          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div className="px-6 py-4 border-b border-gray-100">
              <h2 className="text-xl font-bold text-gray-900">Individual Votes</h2>
            </div>
            <div className="overflow-x-auto">
              <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Voter</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Selection</th>
                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                  </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                  {votes.map((vote) => (
                    <tr key={vote.id} className="hover:bg-gray-50">
                      <td className="px-6 py-4 whitespace-nowrap">
                        <div className="flex items-center">
                          <div className="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                            <User className="w-4 h-4 text-indigo-600" />
                          </div>
                          <span className="text-sm font-medium text-gray-900">
                            {/* Assuming we get voter info in the response */}
                            {(vote as any).user?.name || 'Anonymous Voter'}
                          </span>
                        </div>
                      </td>
                      <td className="px-6 py-4">
                        <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                          {vote.option?.text}
                        </span>
                      </td>
                      <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {new Date(vote.created_at).toLocaleString()}
                      </td>
                    </tr>
                  ))}
                  {votes.length === 0 && (
                    <tr>
                      <td colSpan={3} className="px-6 py-8 text-center text-gray-500">
                        No votes recorded yet.
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
