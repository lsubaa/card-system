<?php
namespace App\Http\Controllers\Merchant; use App\Library\Response; use Carbon\Carbon; use Illuminate\Http\Request; use App\Http\Controllers\Controller; class Coupon extends Controller { function get(Request $spf631e6) { $spe8afa9 = $this->authQuery($spf631e6, \App\Coupon::class)->with(array('category' => function ($spe8afa9) { $spe8afa9->select(array('id', 'name')); }))->with(array('product' => function ($spe8afa9) { $spe8afa9->select(array('id', 'name')); })); $sp33e19c = $spf631e6->post('search', false); $spdb13aa = $spf631e6->post('val', false); if ($sp33e19c && $spdb13aa) { if ($sp33e19c == 'id') { $spe8afa9->where('id', $spdb13aa); } else { $spe8afa9->where($sp33e19c, 'like', '%' . $spdb13aa . '%'); } } $sp23531a = (int) $spf631e6->post('category_id'); $spa20daf = $spf631e6->post('product_id', -1); if ($sp23531a > 0) { if ($spa20daf > 0) { $spe8afa9->where('product_id', $spa20daf); } else { $spe8afa9->where('category_id', $sp23531a); } } $sp73477e = $spf631e6->post('status'); if (strlen($sp73477e)) { $spe8afa9->whereIn('status', explode(',', $sp73477e)); } $sp4c5fa8 = $spf631e6->post('type'); if (strlen($sp4c5fa8)) { $spe8afa9->whereIn('type', explode(',', $sp4c5fa8)); } $spe8afa9->orderByRaw('expire_at DESC,category_id,product_id,type,status'); $sp079e8f = $spf631e6->post('current_page', 1); $sp08a323 = $spf631e6->post('per_page', 20); $sp3562f5 = $spe8afa9->paginate($sp08a323, array('*'), 'page', $sp079e8f); return Response::success($sp3562f5); } function create(Request $spf631e6) { $sp7548f9 = $spf631e6->post('count', 0); $sp4c5fa8 = (int) $spf631e6->post('type', \App\Coupon::TYPE_ONETIME); $sp160a23 = $spf631e6->post('expire_at'); $sp4bbeaa = (int) $spf631e6->post('discount_val'); $sp6ee5cf = (int) $spf631e6->post('discount_type', \App\Coupon::DISCOUNT_TYPE_AMOUNT); $sp183304 = $spf631e6->post('remark'); if ($sp6ee5cf === \App\Coupon::DISCOUNT_TYPE_AMOUNT) { if ($sp4bbeaa < 1 || $sp4bbeaa > 1000000000) { return Response::fail('优惠券面额需要在0.01-10000000之间'); } } if ($sp6ee5cf === \App\Coupon::DISCOUNT_TYPE_PERCENT) { if ($sp4bbeaa < 1 || $sp4bbeaa > 100) { return Response::fail('优惠券面额需要在1-100之间'); } } $sp23531a = (int) $spf631e6->post('category_id', -1); $spa20daf = (int) $spf631e6->post('product_id', -1); if ($sp4c5fa8 === \App\Coupon::TYPE_REPEAT) { $spbf61ef = $spf631e6->post('coupon'); if (!$spbf61ef) { $spbf61ef = strtoupper(str_random()); } $spb91d1b = new \App\Coupon(); $spb91d1b->user_id = $this->getUserIdOrFail($spf631e6); $spb91d1b->category_id = $sp23531a; $spb91d1b->product_id = $spa20daf; $spb91d1b->coupon = $spbf61ef; $spb91d1b->type = $sp4c5fa8; $spb91d1b->discount_val = $sp4bbeaa; $spb91d1b->discount_type = $sp6ee5cf; $spb91d1b->count_all = (int) $spf631e6->post('count_all', 1); if ($spb91d1b->count_all < 1 || $spb91d1b->count_all > 10000000) { return Response::fail('可用次数不能超过10000000'); } $spb91d1b->expire_at = $sp160a23; $spb91d1b->saveOrFail(); return Response::success(array($spb91d1b->coupon)); } elseif ($sp4c5fa8 === \App\Coupon::TYPE_ONETIME) { if (!$sp7548f9) { return Response::forbidden('请输入生成数量'); } if ($sp7548f9 > 100) { return Response::forbidden('每次生成不能大于100张'); } $spe692ec = array(); $sp847c9e = array(); $sp7652a3 = $this->getUserIdOrFail($spf631e6); $sp1537c8 = Carbon::now(); for ($sp218d20 = 0; $sp218d20 < $sp7548f9; $sp218d20++) { $spb91d1b = strtoupper(str_random()); $sp847c9e[] = $spb91d1b; $spe692ec[] = array('user_id' => $sp7652a3, 'coupon' => $spb91d1b, 'category_id' => $sp23531a, 'product_id' => $spa20daf, 'type' => $sp4c5fa8, 'discount_val' => $sp4bbeaa, 'discount_type' => $sp6ee5cf, 'status' => \App\Coupon::STATUS_NORMAL, 'remark' => $sp183304, 'created_at' => $sp1537c8, 'expire_at' => $sp160a23); } \App\Coupon::insert($spe692ec); return Response::success($sp847c9e); } else { return Response::forbidden('unknown type: ' . $sp4c5fa8); } } function edit(Request $spf631e6) { $spcbbf66 = (int) $spf631e6->post('id'); $spbf61ef = $spf631e6->post('coupon'); $sp23531a = (int) $spf631e6->post('category_id', -1); $spa20daf = (int) $spf631e6->post('product_id', -1); $sp160a23 = $spf631e6->post('expire_at', NULL); $sp73477e = (int) $spf631e6->post('status', \App\Coupon::STATUS_NORMAL); $sp4c5fa8 = (int) $spf631e6->post('type', \App\Coupon::TYPE_ONETIME); $sp4bbeaa = (int) $spf631e6->post('discount_val'); $sp6ee5cf = (int) $spf631e6->post('discount_type', \App\Coupon::DISCOUNT_TYPE_AMOUNT); if ($sp6ee5cf === \App\Coupon::DISCOUNT_TYPE_AMOUNT) { if ($sp4bbeaa < 1 || $sp4bbeaa > 1000000000) { return Response::fail('优惠券面额需要在0.01-10000000之间'); } } if ($sp6ee5cf === \App\Coupon::DISCOUNT_TYPE_PERCENT) { if ($sp4bbeaa < 1 || $sp4bbeaa > 100) { return Response::fail('优惠券面额需要在1-100之间'); } } $spb91d1b = $this->authQuery($spf631e6, \App\Coupon::class)->find($spcbbf66); if ($spb91d1b) { $spb91d1b->coupon = $spbf61ef; $spb91d1b->category_id = $sp23531a; $spb91d1b->product_id = $spa20daf; $spb91d1b->status = $sp73477e; $spb91d1b->type = $sp4c5fa8; $spb91d1b->discount_val = $sp4bbeaa; $spb91d1b->discount_type = $sp6ee5cf; if ($sp4c5fa8 === \App\Coupon::TYPE_REPEAT) { $spb91d1b->count_all = (int) $spf631e6->post('count_all', 1); if ($spb91d1b->count_all < 1 || $spb91d1b->count_all > 10000000) { return Response::fail('可用次数不能超过10000000'); } } if ($sp160a23) { $spb91d1b->expire_at = $sp160a23; } $spb91d1b->saveOrFail(); } else { $sp7958ee = explode('
', $spbf61ef); for ($sp218d20 = 0; $sp218d20 < count($sp7958ee); $sp218d20++) { $sp9927e5 = str_replace('