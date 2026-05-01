interface ResultsBarProps {
  text: string;
  count: number;
  percentage: number;
}

export default function ResultsBar({ text, count, percentage }: ResultsBarProps) {
  return (
    <div className="space-y-2">
      <div className="flex justify-between text-sm font-medium">
        <span className="text-gray-900">{text}</span>
        <span className="text-gray-500">
          {count} votes ({percentage.toFixed(1)}%)
        </span>
      </div>
      <div className="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
        <div
          className="bg-indigo-600 h-full rounded-full transition-all duration-500 ease-out"
          style={{ width: `${percentage}%` }}
        />
      </div>
    </div>
  );
}
