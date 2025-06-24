package com.example.tluresourcebooker.adapter;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.example.tluresourcebooker.R;
import com.example.tluresourcebooker.model.Room;
import java.util.List;

public class MainListAdapter extends RecyclerView.Adapter<RecyclerView.ViewHolder> {

    private static final int VIEW_TYPE_HEADER = 0;
    private static final int VIEW_TYPE_ROOM_LIST = 1;

    private List<Object> items;
    private HorizontalRoomAdapter.OnRoomClickListener roomClickListener;

    public MainListAdapter(List<Object> items, HorizontalRoomAdapter.OnRoomClickListener listener) {
        this.items = items;
        this.roomClickListener = listener;
    }

    @Override
    public int getItemViewType(int position) {
        if (items.get(position) instanceof String) {
            return VIEW_TYPE_HEADER;
        } else if (items.get(position) instanceof List) {
            return VIEW_TYPE_ROOM_LIST;
        }
        return super.getItemViewType(position);
    }

    @NonNull
    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        LayoutInflater inflater = LayoutInflater.from(parent.getContext());
        if (viewType == VIEW_TYPE_HEADER) {
            View view = inflater.inflate(R.layout.item_section_header, parent, false);
            return new HeaderViewHolder(view);
        } else { // VIEW_TYPE_ROOM_LIST
            View view = inflater.inflate(R.layout.item_room_section, parent, false);
            return new RoomListViewHolder(view);
        }
    }

    @Override
    public void onBindViewHolder(@NonNull RecyclerView.ViewHolder holder, int position) {
        if (holder.getItemViewType() == VIEW_TYPE_HEADER) {
            ((HeaderViewHolder) holder).bind((String) items.get(position));
        } else {
            if (items.get(position) instanceof List) {
                @SuppressWarnings("unchecked")
                List<Room> roomList = (List<Room>) items.get(position);
                // === SỬA Ở ĐÂY: TRUYỀN LISTENER VÀO HÀM BIND ===
                ((RoomListViewHolder) holder).bind(roomList, roomClickListener);
            }
        }
    }

    @Override
    public int getItemCount() {
        return items != null ? items.size() : 0;
    }

    // ViewHolder cho Tiêu đề (Header)
    static class HeaderViewHolder extends RecyclerView.ViewHolder {
        TextView sectionTitle;
        public HeaderViewHolder(@NonNull View itemView) {
            super(itemView);
            sectionTitle = itemView.findViewById(R.id.textViewSectionTitle);
        }
        void bind(String title) {
            sectionTitle.setText(title);
        }
    }

    // ViewHolder cho Danh sách phòng ngang (Room List)
    static class RoomListViewHolder extends RecyclerView.ViewHolder {
        RecyclerView horizontalRecyclerView;
        public RoomListViewHolder(@NonNull View itemView) {
            super(itemView);
            horizontalRecyclerView = itemView.findViewById(R.id.horizontalRecyclerView);
        }

        // === SỬA Ở ĐÂY: THÊM LISTENER VÀO ĐỊNH NGHĨA HÀM BIND ===
        void bind(List<Room> roomList, HorizontalRoomAdapter.OnRoomClickListener listener) {
            HorizontalRoomAdapter adapter = new HorizontalRoomAdapter(roomList, listener);
            horizontalRecyclerView.setAdapter(adapter);
        }
    }
}