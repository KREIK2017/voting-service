'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import api from '@/lib/api';
import { Poll, Option } from '@/lib/types';
import { Check } from 'lucide-react';

interface VoteFormProps {
  poll: Poll;
}

export default function VoteForm({ poll }: VoteFormProps) {
  const router = useRouter();
  const [selectedOptions, setSelectedOptions] = useState<number[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const toggleOption = (id: number) => {
    if (poll.allow_multiple) {
      setSelectedOptions((prev) =>
        prev.includes(id) ? prev.filter((i) => i !== id) : [...prev, id]
      );
    } else {
      setSelectedOptions([id]);
    }
    setError(null);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (selectedOptions.length === 0) {
      setError('Please select at least one option.');
      return;
    }

    setLoading(true);
    try {
      await api.post(`/polls/${poll.id}/vote`, {
        option_ids: selectedOptions,
      });
      router.push(`/polls/${poll.id}/results`);
    } catch (err: any) {
      setError(err.response?.data?.message || 'Failed to submit vote. You might have already voted.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      {error && (
        <div className="p-4 text-sm text-red-700 bg-red-100 rounded-lg">
          {error}
        </div>
      )}

      <div className="space-y-3">
        {poll.options?.map((option) => (
          <div
            key={option.id}
            onClick={() => toggleOption(option.id)}
            className={`relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 ${
              selectedOptions.includes(option.id)
                ? 'border-indigo-600 bg-indigo-50'
                : 'border-gray-200 hover:border-gray-300'
            }`}
          >
            <div className="flex-1">
              <span className={`text-lg font-medium ${
                selectedOptions.includes(option.id) ? 'text-indigo-900' : 'text-gray-900'
              }`}>
                {option.text}
              </span>
            </div>
            {selectedOptions.includes(option.id) && (
              <div className="flex-shrink-0 bg-indigo-600 rounded-full p-1">
                <Check className="w-4 h-4 text-white" />
              </div>
            )}
          </div>
        ))}
      </div>

      <button
        type="submit"
        disabled={loading || selectedOptions.length === 0}
        className="w-full py-4 bg-indigo-600 text-white rounded-xl font-bold text-lg shadow-lg hover:bg-indigo-700 transition-colors disabled:bg-indigo-300 disabled:shadow-none mt-8"
      >
        {loading ? 'Submitting...' : 'Cast Your Vote'}
      </button>
      
      <p className="text-center text-sm text-gray-500 italic">
        {poll.allow_multiple ? 'You can select multiple options.' : 'You can only select one option.'}
      </p>
    </form>
  );
}
